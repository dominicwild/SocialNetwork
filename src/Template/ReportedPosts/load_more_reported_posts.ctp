<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReportedPost[]|\Cake\Collection\CollectionInterface $reportedPosts
 */

?>

<?php if(sizeof($reports) > 0): ?>

    <?php
    foreach ($reports as $report) {
        echo $this->element("reported_post", ["report" => $report]);
    }
    ?>

<?php else: ?>

    <div class="card text-center text-white bg-info my-3" id="admin-end-load">
        <div>
            There are no more reported posts.
        </div>
        <div class="my-2">
            <img src="/img/si-glyph-clock.svg" style="width: 64px; height: 64px;">
        </div>
    </div>

<?php endif; ?>
