<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poll[]|\Cake\Collection\CollectionInterface $polls
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Poll'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Poll Votes'), ['controller' => 'PollVotes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll Vote'), ['controller' => 'PollVotes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="polls index large-9 medium-8 columns content">
    <h3><?= __('Polls') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('post_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_add_options') ?></th>
                <th scope="col"><?= $this->Paginator->sort('expires') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($polls as $poll): ?>
            <tr>
                <td><?= $this->Number->format($poll->id) ?></td>
                <td><?= $poll->has('post') ? $this->Html->link($poll->post->id, ['controller' => 'Posts', 'action' => 'view', $poll->post->id]) : '' ?></td>
                <td><?= h($poll->user_add_options) ?></td>
                <td><?= $this->Number->format($poll->expires) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $poll->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $poll->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $poll->id], ['confirm' => __('Are you sure you want to delete # {0}?', $poll->id)]) ?>
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
