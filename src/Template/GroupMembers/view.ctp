<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GroupMember $groupMember
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Group Member'), ['action' => 'edit', $groupMember->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Group Member'), ['action' => 'delete', $groupMember->id], ['confirm' => __('Are you sure you want to delete # {0}?', $groupMember->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Group Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group Member'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="groupMembers view large-9 medium-8 columns content">
    <h3><?= h($groupMember->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $groupMember->has('user') ? $this->Html->link($groupMember->user->id, ['controller' => 'Users', 'action' => 'view', $groupMember->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Group') ?></th>
            <td><?= $groupMember->has('group') ? $this->Html->link($groupMember->group->name, ['controller' => 'Groups', 'action' => 'view', $groupMember->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($groupMember->id) ?></td>
        </tr>
    </table>
</div>
