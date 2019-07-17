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

$end_date = $event->end_date == null ? "" : $this->Miscellaneous->eventTime($event->end_date,true);
if($event->end_date != null){
    $start_date = $this->Miscellaneous->eventTime($event->date,true);
    $date_string = $start_date . " — " . $end_date;
} else {
    $date_string = $this->Miscellaneous->eventTime($event->date);
}
?>
<div class="row mt-3">
    <div class="col-12">
        <div class="card search-result">
            <div class="card-body search-main p-0">
                <div class="search-summary">
                    <div class="search-image">
                        <img src="<?= $event->image ?>">
                    </div>
                    <div class="search-image-title">
                        <h5 class="m-0 p-1"><?= $this->Miscellaneous->processContent($event->title) ?></h5>
                    </div>
                </div>
                <div class="search-info">
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-event-place-icon" src="/img/map-marker.svg">
                        <?= $this->Miscellaneous->processContent($event->place) ?>
                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1" src="/img/calendar.svg"> <?= $date_string ?>
                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-event-group-icon" src="/img/si-glyph-share-1.svg">
                        <?= $this->Miscellaneous->processContent($event->group->name)?>
                    </div>
                </div>
                <div class="search-content py-1 px-2">
                    <h5 class="mb-1"><u>Event Details</u> <a href="<?= Router::url(["controller" => "Events", "action" => "index", "id" => $event->id])?>" target="_blank">[Go to]</a></h5>
                    <p class="search-text"><?= $this->Miscellaneous->processContent($event->post->content) ?></p>
                </div>
                <div class="search-type p-1 event-type">
                    <img class="search-type-icon event-type-icon" src="/img/si-glyph-calendar-3.svg">
                    <h4 class="mb-0 mt-2">Event</h4>
                </div>
            </div>
        </div>
    </div>
</div>