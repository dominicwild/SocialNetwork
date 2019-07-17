<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>

<?php if (isset($post->polls[0])): ?>
    <?php foreach($post->polls as $poll): ?>
        <?php

        if($poll->expires > time() || $poll->expires == -1){
            $expired = false;
            $inactive = "";
        } else {
            $expired = true;
            $inactive = "inactive";
        }

        ?>
        <div class="row poll-display-container hover-effect mx-2 mb-2 <?= $inactive ?>" data-id="<?= $poll->id ?>">

            <div class="col-12 p-0 d-flex poll-top" title="View Poll">
                <div class="col-1 pr-2 p-0  d-flex align-items-center justify-content-center">
                    <img class="poll-arrow-down" src="/img/TriangleArrow-Down.svg">
                </div>
                <div class="col-9 p-0 py-2 m-0 mb-1 d-flex align-items-center">
                    <span class="poll-display-question"><?= $poll->question ?></span>
                </div>
                <div class="col-2 pr-1 d-flex align-items-center justify-content-center poll-top-btn">
                    <?php if ($poll->has_voted || $expired): ?>
                        <button class="btn btn-secondary btn-block poll-view-voters-btn float-right p-1" title="View Voters" data-toggle=0 style="display:none;">
                            View Voters
                        </button>
                    <?php else: ?>
                        <button class="btn btn-info btn-block poll-vote-btn float-right p-1" title="Submit Vote" style="display:none;">Vote</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 p-0 poll-bottom" style="display:none;">
                <form class="col-12 mb-2 poll-vote-form" type="post">
                    <?php if(!$poll->has_voted && !$expired): ?>
                        <?= $this->element("poll-vote", ["poll" => $poll]); ?>
                    <?php else: ?>
                        <?= $this->element("poll-results", ["poll" => $poll])?>
                    <?php endif ?>
                </form>
                <?php if($poll->user_add_options && !$expired): ?>
                    <?php

                    if(!$poll->has_voted){
                        $add_display = "";
                    } else {
                        $add_display = "none";
                    }

                    ?>
                    <div class="col-12 poll-add-option-container mb-2" style="display:<?= $add_display ?>">
                        <div class="poll-add-option-btn d-flex py-1 justify-content-center align-items-center" title="Add an option to poll">
                            <img class="add-option-icon" src="/img/si-glyph-button-plus.svg">
                        </div>
                    </div>
                <?php endif; ?>
                    <div class="col-12 poll-end-container mb-1 pr-1 d-flex">
                        <span class="col-9 pl-0 align-items-center d-flex">
                            <small class="text-muted poll-info-votes"><?= $this->Miscellaneous->voteText($poll->total_votes) ?></small>
                            <?php if($poll->expires != -1): ?>
                                <?php
                                    if($expired){
                                        $expire_text = "Expired on " . $this->Miscellaneous->toEditTimeFormat($poll->expires);
                                    } else {
                                        $expire_text = $this->Miscellaneous->expireText($poll->expires);
                                    }
                                ?>
                                <small class="text-muted poll-info-expire">&nbsp;·&nbsp;<?= $expire_text ?></small>
                            <?php endif; ?>
                        </span>
                        <?php if($poll->redo): ?>
                            <span class="col-3 pl-0 poll-redo-container float-right pr-0" style="display: <?= $poll->has_voted ? "initial" : "none" ?>">
                                <button class="btn btn-outline-info float-right py-1 px-2 poll-redo-btn">Undo</button>
                            </span>
                        <?php endif; ?>
                    </div>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>