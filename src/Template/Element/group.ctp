<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Group $group
 * @var MiscellaneousHelper $Miscellaneous
 */

use App\View\Helper\MiscellaneousHelper;
use Cake\Routing\Router;

?>



    <div class="card mb-4 shadow-sm group" data-group-id="<?= $group->id ?>">
        <a class="group-link" href="<?= Router::url(["controller" => "Groups", "action" => "view", "id" => $group->id]) ?>">
            <img class="card-img-top event-image" src="<?= $group->image ?>" data-holder-rendered="true">
            <div class="card-body p-2 d-flex flex-column">
                <h5 class="card-title"><?= $group->name ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Last activity: <?= $this->Miscellaneous->formatTime($group->recent_time) ?></h6>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <?= $this->element("join-group-btn", ["group" => $group]) ?>
                    </div>
                    <span>
                        <img src="/img/person.svg"><small class="ml-2 member-count"><?= $group->num_members ?></small>
                    </span>
                </div>
            </div>
        </a>
    </div>


