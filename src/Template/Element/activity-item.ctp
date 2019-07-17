<?php
/**
 * @var \App\Model\Entity\Activity $activity
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>

<li class="list-group-item list-group-item-info p-2 animated activity-item" data-activity-id="<?= $activity->id ?>">
    <a href="<?php echo $activity->link ?>" title="View Activity">
        <div class="media small">
            <img class="bd-placeholder-img mr-2 rounded-circle activity-user-img" src="<?= $this->Miscellaneous->getImage($activity->user->profile_image) ?>" focusable="false" role="img">
            <p class="media-body mb-0">
                    <span class="row m-0 p-0">
                        <strong class="col-9 p-0 m-0 activity-name">
                            <?= $activity->user->FirstName . " " . $activity->user->LastName ?>
                            <small class="text-muted">
                                · <?= $this->Miscellaneous->formatTime($activity->time)?>
                            </small>
                        </strong>
                    </span>
                <span class="activity-content"><?= $activity->description ?></span>
            </p>
        </div>
    </a>
</li>
