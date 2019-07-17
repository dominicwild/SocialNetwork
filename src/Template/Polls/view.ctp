<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poll $poll
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Poll'), ['action' => 'edit', $poll->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Poll'), ['action' => 'delete', $poll->id], ['confirm' => __('Are you sure you want to delete # {0}?', $poll->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Polls'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Poll Options'), ['controller' => 'PollOptions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Option'), ['controller' => 'PollOptions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Poll Votes'), ['controller' => 'PollVotes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Poll Vote'), ['controller' => 'PollVotes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="polls view large-9 medium-8 columns content">
    <h3><?= h($poll->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Post') ?></th>
            <td><?= $poll->has('post') ? $this->Html->link($poll->post->id, ['controller' => 'Posts', 'action' => 'view', $poll->post->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($poll->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Expires') ?></th>
            <td><?= $this->Number->format($poll->expires) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Add Options') ?></th>
            <td><?= $poll->user_add_options ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Question') ?></h4>
        <?= $this->Text->autoParagraph(h($poll->question)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Poll Options') ?></h4>
        <?php if (!empty($poll->poll_options)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Poll Id') ?></th>
                <th scope="col"><?= __('Option Name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($poll->poll_options as $pollOptions): ?>
            <tr>
                <td><?= h($pollOptions->id) ?></td>
                <td><?= h($pollOptions->poll_id) ?></td>
                <td><?= h($pollOptions->option_name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PollOptions', 'action' => 'view', $pollOptions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PollOptions', 'action' => 'edit', $pollOptions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PollOptions', 'action' => 'delete', $pollOptions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollOptions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Poll Votes') ?></h4>
        <?php if (!empty($poll->poll_votes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Poll Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Option Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($poll->poll_votes as $pollVotes): ?>
            <tr>
                <td><?= h($pollVotes->poll_id) ?></td>
                <td><?= h($pollVotes->user_id) ?></td>
                <td><?= h($pollVotes->option_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PollVotes', 'action' => 'view', $pollVotes->poll_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PollVotes', 'action' => 'edit', $pollVotes->poll_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PollVotes', 'action' => 'delete', $pollVotes->poll_id], ['confirm' => __('Are you sure you want to delete # {0}?', $pollVotes->poll_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
