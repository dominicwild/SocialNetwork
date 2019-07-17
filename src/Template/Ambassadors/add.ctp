<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ambassador $ambassador
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Ambassadors'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="ambassadors form large-9 medium-8 columns content">
    <?= $this->Form->create($ambassador) ?>
    <fieldset>
        <legend><?= __('Add Ambassador') ?></legend>
        <?php
            echo $this->Form->control('remind_time');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
