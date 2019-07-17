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

<div class="row mt-3">
    <div class="col-12">
        <div class="card search-result">
            <div class="card-body search-main p-0">
                <div class="search-summary">
                    <div class="search-image">
                        <img src="<?= $this->Miscellaneous->getImage($group->image) ?>">
                    </div>
                    <div class="search-image-title">
                        <h5 class="m-0 p-1"><?= $this->Miscellaneous->processContent($group->name) ?></h5>
                    </div>
                </div>
                <div class="search-info">
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-event-place-icon" src="/img/people.svg">
                        Members: <span class="ml-1 emphasized-info"><?= $group->num_members ?></span>

                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1" src="/img/si-glyph-bubble-message-talk.svg">
                        Last Active: <span class="ml-1 emphasized-info"><?= $this->Miscellaneous->formatTimeWords($group->recent_time)?></span>
                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-event-group-icon" src="/img/si-glyph-ping-pong-racket.svg">
                        Upcoming Events: <span class="ml-1 emphasized-info"><?= $group->num_upcoming_events ?></span>

                    </div>
                </div>
                <div class="search-content py-1 px-2">
                    <h5 class="mb-1"><u>Group Description</u> <a href="<?= Router::url(["controller" => "Groups", "action" => "view", "id" => $group->id])?>" target="_blank">[Go to]</a></h5>
                    <p class="search-text"><?= $this->Miscellaneous->processContent($group->description) ?></p>
                </div>
                <div class="search-type group-type p-1">
                    <img class="search-type-icon group-type-icon" src="/img/si-glyph-flag.svg">
                    <h4 class="mb-1 mt-2">Group</h4>
                </div>
            </div>
        </div>
    </div>
</div>