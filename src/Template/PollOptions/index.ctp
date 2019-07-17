<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollOption[]|\Cake\Collection\CollectionInterface $pollOptions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Poll Option'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Polls'), ['controller' => 'Polls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll'), ['controller' => 'Polls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pollOptions index large-9 medium-8 columns content">
    <h3><?= __('Poll Options') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('poll_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pollOptions as $pollOption): ?>
            <tr>
                <td><?= $this->Number->format($pollOption->id) ?></td>
                <td><?= $pollOption->has('poll') ? $this->Html->link($pollOption->poll->id, ['controller' => 'Polls', 'action' => 'view', $pollOption->poll->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $pollOption->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $pollOption->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $pollOption->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollOption->id)]) ?>
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
