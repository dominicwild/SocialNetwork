<?php
/**
 * @var \App\Model\Entity\Activity $activity
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 *
 */
use Cake\Routing\Router;
?>

<?php

if(count($activity->toArray()) == 0){
    $arrows_enabled = "disabled";
} else {
    $arrows_enabled = "";
}

?>

<?= $this->Html->css("recent-activity.css") ?>
<?= $this->element("recent-activity-js") ?>


<div class="card recent-activity">
    <div class="card-header p-2">
        Recent Activity
    </div>
    <div class="card-body text-muted p-0 activity-controls">
        <div class="btn-group w-100" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary activity-arrow-left" <?= $arrows_enabled ?> title="Previous Page"><img src="/img/si-glyph-arrow-thick-left.svg"></button>
            <button type="button" class="btn btn-secondary activity-arrow-right" <?= $arrows_enabled ?> title="Next Page"><img src="/img/si-glyph-arrow-thick-right.svg"></button>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <?php
        $display = "style='display:none;'";
        $activity = $activity->toArray();
        if($activity && $activity != []){
            foreach($activity as $a){
                $this->set("activity", $a);
                echo $this->element("activity-item");
            }
        } else {
            $display = "";
        }
        ?>
    </ul>
    <div class="card-footer text-muted p-0 activity-footer text-center justify-content-center align-items-center" <?= $display ?>>
        <i class="text-muted my-auto">
            There has been no activity.
        </i>
    </div>
    <div class="card-footer text-center p-1">
        Page <span class="activity-page-number">1</span>
    </div>
</div>