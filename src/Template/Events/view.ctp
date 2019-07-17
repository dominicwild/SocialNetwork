<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Event'), ['action' => 'edit', $event->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Event'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Event Participants'), ['controller' => 'EventParticipants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event Participant'), ['controller' => 'EventParticipants', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="events view large-9 medium-8 columns content">
    <h3><?= h($event->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Post') ?></th>
            <td><?= $event->has('post') ? $this->Html->link($event->post->id, ['controller' => 'Posts', 'action' => 'view', $event->post->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Group') ?></th>
            <td><?= $event->has('group') ? $this->Html->link($event->group->name, ['controller' => 'Groups', 'action' => 'view', $event->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Place') ?></th>
            <td><?= h($event->place) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($event->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($event->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= $this->Number->format($event->date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Event Participants') ?></h4>
        <?php if (!empty($event->event_participants)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Event Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($event->event_participants as $eventParticipants): ?>
            <tr>
                <td><?= h($eventParticipants->id) ?></td>
                <td><?= h($eventParticipants->user_id) ?></td>
                <td><?= h($eventParticipants->event_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EventParticipants', 'action' => 'view', $eventParticipants->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EventParticipants', 'action' => 'edit', $eventParticipants->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EventParticipants', 'action' => 'delete', $eventParticipants->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eventParticipants->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
