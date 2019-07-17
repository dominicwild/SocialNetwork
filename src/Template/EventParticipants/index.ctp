<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EventParticipant[]|\Cake\Collection\CollectionInterface $eventParticipants
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Event Participant'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Events'), ['controller' => 'Events', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="eventParticipants index large-9 medium-8 columns content">
    <h3><?= __('Event Participants') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('event_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventParticipants as $eventParticipant): ?>
            <tr>
                <td><?= $this->Number->format($eventParticipant->id) ?></td>
                <td><?= $eventParticipant->has('user') ? $this->Html->link($eventParticipant->user->id, ['controller' => 'Users', 'action' => 'view', $eventParticipant->user->id]) : '' ?></td>
                <td><?= $eventParticipant->has('event') ? $this->Html->link($eventParticipant->event->title, ['controller' => 'Events', 'action' => 'view', $eventParticipant->event->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $eventParticipant->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $eventParticipant->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $eventParticipant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eventParticipant->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
