<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PollUserOption $pollUserOption
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $pollUserOption->user_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $pollUserOption->user_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Poll User Options'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pollUserOptions form large-9 medium-8 columns content">
    <?= $this->Form->create($pollUserOption) ?>
    <fieldset>
        <legend><?= __('Edit Poll User Option') ?></legend>
        <?php
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
