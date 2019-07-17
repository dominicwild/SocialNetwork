<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Group $group
 * @var \App\Model\Entity\Event $event
 * @var MiscellaneousHelper $Miscellaneous
 */
use App\View\Helper\MiscellaneousHelper;
use Cake\Routing\Router;
?>

<div class="col-md-3 py-1 d-flex event-card-container">
    <div class="card mb-4 shadow-sm event" data-event-id="<?= $event->id ?>">
        <div>
            <img class="card-img-top event-image" src="<?= $this->Miscellaneous->getImage($event->image) ?>" data-holder-rendered="true">
        </div>
        <div class="card-body p-2 d-flex flex-column">
            <h5 class="card-title"><?= h($event->title) ?></h5>
            <?php if($event->end_date != null || $event->end_date > 0): ?>
            <h6 class="card-subtitle mb-2 text-muted time"><?= $this->Miscellaneous->eventTime($event->date) . " - " . $this->Miscellaneous->eventTime($event->end_date)?></h6>
            <?php else: ?>
            <h6 class="card-subtitle mb-2 text-muted time"><?= $this->Miscellaneous->eventTime($event->date)?></h6>
            <?php endif; ?>
            <p class="card-text">At <?= h($event->place) ?></p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <?php if(!isset($email_version)): ?>
                        <?php if($event->user_in_event):?>
                            <button type="button" class="btn btn-sm btn-outline-danger leave-btn">Leave</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-success join-btn">Join</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <span>
                    <img src="/img/person.svg"><small class="ml-2 member-count"><?= $event->amount_at_event ?></small>
                    </span>
                <small class="text-muted">Posted <?= $this->Miscellaneous->formatTimeWords($event->post->created_time)?></small>
            </div>
        </div>
    </div>
</div>