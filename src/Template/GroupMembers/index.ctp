<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GroupMember[]|\Cake\Collection\CollectionInterface $groupMembers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Group Member'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="groupMembers index large-9 medium-8 columns content">
    <h3><?= __('Group Members') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('group_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groupMembers as $groupMember): ?>
            <tr>
                <td><?= $this->Number->format($groupMember->id) ?></td>
                <td><?= $groupMember->has('user') ? $this->Html->link($groupMember->user->id, ['controller' => 'Users', 'action' => 'view', $groupMember->user->id]) : '' ?></td>
                <td><?= $groupMember->has('group') ? $this->Html->link($groupMember->group->name, ['controller' => 'Groups', 'action' => 'view', $groupMember->group->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $groupMember->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $groupMember->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $groupMember->id], ['confirm' => __('Are you sure you want to delete # {0}?', $groupMember->id)]) ?>
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
