<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollOption $pollOption
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Poll Options'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Polls'), ['controller' => 'Polls', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll'), ['controller' => 'Polls', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pollOptions form large-9 medium-8 columns content">
    <?= $this->Form->create($pollOption) ?>
    <fieldset>
        <legend><?= __('Add Poll Option') ?></legend>
        <?php
            echo $this->Form->control('poll_id', ['options' => $polls]);
            echo $this->Form->control('option_name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
