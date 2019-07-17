<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Routing\Router;
?>

<?php

$type = $poll->multi ? "checkbox" : "radio";

if(!isset($no_hidden)){
    $no_hidden = false;
}
?>

<?php foreach($poll->poll_options as $option): ?>
    <div class="d-flex mb-1 poll-option-container">
        <?php if($type === "radio"): ?>
            <?php $id = $option->option_name;?>
            <div class="col-11 custom-control custom-radio poll-display-radio">
                <input type="radio" id="<?= $id ?>" value="<?= $option->id ?>" name="<?= "poll-" . $poll->id ?>" class="custom-control-input">
                <label class="custom-control-label" for="<?= $id ?>">
                    <?= $option->option_name ?>
                </label>
            </div>
        <?php else: ?>
            <?php $id = $option->id; ?>
            <div class="col-11 custom-control custom-checkbox poll-display-checkbox">
                <input type="checkbox" id="<?= $id ?>" value="<?= $option->id ?>" name="<?= $option->option_name ?>" class="custom-control-input">
                <label class="custom-control-label" for="<?= $id ?>">
                    <?= $option->option_name ?>
                </label>
            </div>
        <?php endif; ?>
        <?php if($option->is_user_option && $option->poll_votes == []): ?>
            <div class="col-1 poll-remove-option p-0 pl-1">
                <button class="btn btn-danger p-0 poll-btn-remove-option" type="button">
                    <img src="/img/x.svg">
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php if(!$no_hidden): ?>
    <input type="hidden" name="id" value="<?= $poll->id ?>">
<?php endif; ?>