<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GroupPost $groupPost
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Group Post'), ['action' => 'edit', $groupPost->group_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Group Post'), ['action' => 'delete', $groupPost->group_id], ['confirm' => __('Are you sure you want to delete # {0}?', $groupPost->group_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Group Posts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group Post'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="groupPosts view large-9 medium-8 columns content">
    <h3><?= h($groupPost->group_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Group') ?></th>
            <td><?= $groupPost->has('group') ? $this->Html->link($groupPost->group->name, ['controller' => 'Groups', 'action' => 'view', $groupPost->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Post') ?></th>
            <td><?= $groupPost->has('post') ? $this->Html->link($groupPost->post->id, ['controller' => 'Posts', 'action' => 'view', $groupPost->post->id]) : '' ?></td>
        </tr>
    </table>
</div>
