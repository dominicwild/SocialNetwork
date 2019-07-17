<?php
/**
 * @var \App\Model\Entity\Activity $activity
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\ReportedPost $report
 */
use Cake\Routing\Router;
?>

<div class="card mt-3 border-dark report" data-report-id="<?=  $report->id ?>" data-reported-post-id="<?= $report->post_id ?>">
    <div class="card-body p-2 report-reason-body">
        <div class="card-title row">
            <h5 class="col-6 mb-0">
                <u>Reported by:</u>
                <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $user->id])?>">
                    <?= $this->Miscellaneous->processContent($report->user->FirstName) . " " . $this->Miscellaneous->processContent($report->user->LastName) ?>
                </a>
            </h5>
            <div class="col-6 text-right">
                <small class="text-muted"><i><?= $this->Miscellaneous->formatTimeWords($report->date)?></i></small>
            </div>
        </div>
        <p class="card-text"><b><u>Reason:</u></b> <?= $this->Miscellaneous->processContent($report->reason) ?></p>
    </div>
    <div class="card-body p-0 reported-post-view">
        <button class="btn btn-block btn-info py-1">View Post</button>
    </div>
</div>
