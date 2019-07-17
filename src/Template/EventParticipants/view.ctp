<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EventParticipant $eventParticipant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Event Participant'), ['action' => 'edit', $eventParticipant->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Event Participant'), ['action' => 'delete', $eventParticipant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eventParticipant->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Event Participants'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event Participant'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['controller' => 'Events', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="eventParticipants view large-9 medium-8 columns content">
    <h3><?= h($eventParticipant->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $eventParticipant->has('user') ? $this->Html->link($eventParticipant->user->id, ['controller' => 'Users', 'action' => 'view', $eventParticipant->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Event') ?></th>
            <td><?= $eventParticipant->has('event') ? $this->Html->link($eventParticipant->event->title, ['controller' => 'Events', 'action' => 'view', $eventParticipant->event->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($eventParticipant->id) ?></td>
        </tr>
    </table>
</div>
