<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollUserOption $pollUserOption
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Poll User Option'), ['action' => 'edit', $pollUserOption->user_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Poll User Option'), ['action' => 'delete', $pollUserOption->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollUserOption->user_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Poll User Options'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll User Option'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pollUserOptions view large-9 medium-8 columns content">
    <h3><?= h($pollUserOption->user_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $pollUserOption->has('user') ? $this->Html->link($pollUserOption->user->id, ['controller' => 'Users', 'action' => 'view', $pollUserOption->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Poll Option') ?></th>
            <td><?= $pollUserOption->has('poll_option') ? $this->Html->link($pollUserOption->poll_option->id, ['controller' => 'PollOptions', 'action' => 'view', $pollUserOption->poll_option->id]) : '' ?></td>
        </tr>
    </table>
</div>
