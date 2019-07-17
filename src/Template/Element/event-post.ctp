<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Group $group
 * @var MiscellaneousHelper $Miscellaneous
 * @var \App\Model\Entity\Event $event
 */

use App\View\Helper\MiscellaneousHelper;
use Cake\Routing\Router;


?>

<div class="row">
    <div class="col-4">
        <div class="card mb-4 shadow-sm mt-3 event" data-event-id="<?= $event->id ?>">
            <?php if($event->post->user->id == $_SESSION["Auth"]["User"]["id"]): ?>
            <?= $this->Form->create(false,["method" => "get", "action" => "edit", "controller" => "Events"]); ?>
                <button class="btn btn-block btn-primary event-edit-btn"  type="submit" style="border-radius: 10px 10px 0 0;border-bottom-width: 0px;">Edit Event</button>
                <input type="hidden" name="id" value="<?= $event->id ?>">
            <?= $this->Form->end(); ?>
            <?php endif; ?>
            <img class="card-img event-image" src="<?= $this->Miscellaneous->getImage($event->image); ?>">
            <div class="card-body p-2 d-flex flex-column">
                <h5 class="card-title mb-1"><?= h($event->title) ?></h5>
                <div class="mt-auto d-flex justify-content-between align-items-center mb-2">
                    <h6 class=" mb-0 text-muted"><?=$this->Miscellaneous->eventTime($event->date)?></h6>
                    <span class="card-text">At <?=h($event->place)?></span>
                </div>
                <div style="text-align: center;">
                    <?php if($event->user_in_event):?>
                        <button type="button" class="btn btn-sm btn-danger event-post-join-leave-btn animated leave-large-btn">Leave Event</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm btn-success event-post-join-leave-btn animated join-large-btn">Join Event</button>
                    <?php endif; ?>
                </div>
            </div>
            <ul class="list-group list-group-flush ml-0">

                <li class="list-group-item px-2 py-2" style="background-color:
            rgb(243,
            243, 243)
        ">
                <span class="row m-0 p-0">
                    <strong class="col-12 p-0 m-0 text-muted" style="font-size: 12pt;">Attending - <?=$event->amount_at_event?></strong>
                </span>
                    <span class="row m-0 p-0">
                        <?php foreach($event->event_participants as $participant): ?>
                    <div class="col-2 p-0">
                        <?php if(isset($participant)): ?>
                            <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $participant->user->id])?>"><img class="rounded-circle participant-image" src="<?= $this->Miscellaneous->getImage($participant->user->profile_image) ?>" ></a>
                        <?php else: ?>
                            <img class="rounded-circle participant-image" src="<?= $this->Miscellaneous->getImage("The default Image") ?>" >
                        <?php endif; ?>
                    </div>
                        <?php endforeach; ?>
                </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-8">
        <?php
        $this->set("post", $event->post);
        if(isset($limit)){
            $this->set("limit",$limit);
        } else {
            $this->set("limit",3);
        }

        if(isset($link_comment_id)){
            echo $this->element("post", ["link_comment_id" => $link_comment_id]);
        } elseif(isset($highlight)) {
            echo $this->element("post", ["highlight" => $highlight]);
        } else {
            echo $this->element("post");
        }
        ?>
    </div>
</div>