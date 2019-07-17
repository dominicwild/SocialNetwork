<?php
/**
 * @var \Cake\View\View $this
 */
use Cake\Routing\Router;
?>

<?php //debug($poll) ?>

<?php foreach ($poll->poll_options as $option): ?>
    <div class="poll-vote-outcome-container">
        <div class="poll-vote-outcome">
            <span class="poll-vote-bar <?= $option->percent == $poll->max_percent ? "max" : "" ?>" style="width:<?= $option->percent + 0.3 . "%" ?>;"></span>
            <span class="poll-vote-option">
                <span class="poll-vote-percent"> <?= round($option->percent,2) ?>% </span>
                <span class="poll-vote-option"><?= $option->option_name ?></span>
                <?php if($option->voted_for): ?>
                    <img class="float-right" src="/img/si-glyph-square-checked.svg">
                <?php endif; ?>
            </span>
        </div>
        <div class="poll-vote-voters">
            <span class="poll-vote-text align-items-center">
                Voters - <?= count($option->poll_votes) ?>
            </span>
            <span class="poll-vote-images d-flex flex-wrap align-items-center justify-content-center">
                <?php foreach ($option->poll_votes as $vote): ?>
                    <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $vote->user->id])?>">
                        <img class="mr-2 rounded-circle" title="<?= $vote->user->FirstName . " " . $vote->user->LastName ?>" src="<?= $this->Miscellaneous->getImage($vote->user->profile_image) ?>">
                    </a>
                <?php endforeach; ?>
            </span>
        </div>
    </div>
<?php endforeach; ?>
