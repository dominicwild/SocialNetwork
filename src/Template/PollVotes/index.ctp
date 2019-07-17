<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollVote[]|\Cake\Collection\CollectionInterface $pollVotes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Poll Vote'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Polls'), ['controller' => 'Polls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll'), ['controller' => 'Polls', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pollVotes index large-9 medium-8 columns content">
    <h3><?= __('Poll Votes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('poll_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('option_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pollVotes as $pollVote): ?>
            <tr>
                <td><?= $pollVote->has('poll') ? $this->Html->link($pollVote->poll->id, ['controller' => 'Polls', 'action' => 'view', $pollVote->poll->id]) : '' ?></td>
                <td><?= $pollVote->has('user') ? $this->Html->link($pollVote->user->id, ['controller' => 'Users', 'action' => 'view', $pollVote->user->id]) : '' ?></td>
                <td><?= $pollVote->has('poll_option') ? $this->Html->link($pollVote->poll_option->id, ['controller' => 'PollOptions', 'action' => 'view', $pollVote->poll_option->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $pollVote->poll_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $pollVote->poll_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $pollVote->poll_id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollVote->poll_id)]) ?>
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
