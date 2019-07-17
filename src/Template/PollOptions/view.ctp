<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollOption $pollOption
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Poll Option'), ['action' => 'edit', $pollOption->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Poll Option'), ['action' => 'delete', $pollOption->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollOption->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Poll Options'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Option'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Polls'), ['controller' => 'Polls', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll'), ['controller' => 'Polls', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pollOptions view large-9 medium-8 columns content">
    <h3><?= h($pollOption->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Poll') ?></th>
            <td><?= $pollOption->has('poll') ? $this->Html->link($pollOption->poll->id, ['controller' => 'Polls', 'action' => 'view', $pollOption->poll->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($pollOption->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Option Name') ?></h4>
        <?= $this->Text->autoParagraph(h($pollOption->option_name)); ?>
    </div>
</div>
