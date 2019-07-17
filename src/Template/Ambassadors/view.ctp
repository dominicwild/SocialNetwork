<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ambassador $ambassador
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Ambassador'), ['action' => 'edit', $ambassador->user_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Ambassador'), ['action' => 'delete', $ambassador->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $ambassador->user_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ambassadors'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ambassador'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ambassadors view large-9 medium-8 columns content">
    <h3><?= h($ambassador->user_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User Id') ?></th>
            <td><?= $this->Number->format($ambassador->user_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Remind Time') ?></th>
            <td><?= $this->Number->format($ambassador->remind_time) ?></td>
        </tr>
    </table>
</div>
