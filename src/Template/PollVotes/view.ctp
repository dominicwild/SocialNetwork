<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollVote $pollVote
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Poll Vote'), ['action' => 'edit', $pollVote->poll_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Poll Vote'), ['action' => 'delete', $pollVote->poll_id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollVote->poll_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Poll Votes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Vote'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Polls'), ['controller' => 'Polls', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll'), ['controller' => 'Polls', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pollVotes view large-9 medium-8 columns content">
    <h3><?= h($pollVote->poll_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Poll') ?></th>
            <td><?= $pollVote->has('poll') ? $this->Html->link($pollVote->poll->id, ['controller' => 'Polls', 'action' => 'view', $pollVote->poll->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $pollVote->has('user') ? $this->Html->link($pollVote->user->id, ['controller' => 'Users', 'action' => 'view', $pollVote->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Poll Option') ?></th>
            <td><?= $pollVote->has('poll_option') ? $this->Html->link($pollVote->poll_option->id, ['controller' => 'PollOptions', 'action' => 'view', $pollVote->poll_option->id]) : '' ?></td>
        </tr>
    </table>
</div>
