<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ambassador[]|\Cake\Collection\CollectionInterface $ambassadors
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Ambassador'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ambassadors index large-9 medium-8 columns content">
    <h3><?= __('Ambassadors') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('remind_time') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ambassadors as $ambassador): ?>
            <tr>
                <td><?= $this->Number->format($ambassador->user_id) ?></td>
                <td><?= $this->Number->format($ambassador->remind_time) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ambassador->user_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ambassador->user_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ambassador->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $ambassador->user_id)]) ?>
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
