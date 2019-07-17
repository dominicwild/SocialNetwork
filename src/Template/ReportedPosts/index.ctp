<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReportedPost[]|\Cake\Collection\CollectionInterface $reportedPosts
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Reported Post'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reportedPosts index large-9 medium-8 columns content">
    <h3><?= __('Reported Posts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('post_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportedPosts as $reportedPost): ?>
            <tr>
                <td><?= $this->Number->format($reportedPost->id) ?></td>
                <td><?= $reportedPost->has('user') ? $this->Html->link($reportedPost->user->id, ['controller' => 'Users', 'action' => 'view', $reportedPost->user->id]) : '' ?></td>
                <td><?= $reportedPost->has('post') ? $this->Html->link($reportedPost->post->id, ['controller' => 'Posts', 'action' => 'view', $reportedPost->post->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $reportedPost->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportedPost->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportedPost->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportedPost->id)]) ?>
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
