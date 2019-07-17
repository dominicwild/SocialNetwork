<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 * @var \App\Model\Entity\Event $event
 * @var \App\Model\Entity\User $user
 */
use Cake\Routing\Router;


$comment_option1 = "";
$comment_option2 = "";
$comment_option3 = "";
switch ($user->comment_notification_option){
    case 0:
        $comment_option1 = "checked";
        break;
    case 1:
        $comment_option2 = "checked";
        break;
    case 2:
        $comment_option3 = "checked";
        break;
}

?>

<script>

    $(".nav-options").addClass("active");

    $(function(){

        $("#weeklyEmail").on("click", function(e){
            var checked = this.checked;
            var checkBox = this;

            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => "toggleWeekly"]); ?>",
                type: "post",
                data: {weekly_event_email: checked},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if ((data === "true")) {
                        $(checkBox).closest(".options-checkbox").find(".updated-text").css("display","");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $("#perEventEmail").on("click", function(e){
            var checked = this.checked;
            var checkBox = this;

            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => "togglePerEvent"]); ?>",
                type: "post",
                data: {email_per_event: checked},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if ((data === "true")) {
                        $(checkBox).closest(".options-checkbox").find(".updated-text").css("display","");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $("#autoSubcribe").on("click", function(e){
            var checked = this.checked;
            var checkBox = this;

            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => "updateAutoSubscribe"]); ?>",
                type: "post",
                data: {auto_subscribe: checked},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if ((data === "true")) {
                        $(checkBox).closest(".options-checkbox").find(".updated-text").css("display","");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $(".comment-notification-radios input[type=radio]").on("change", function(e){
           var value = this.value;
           var radios = $(this).closest(".comment-notification-radios");

            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => "changeCommentNotifSettings"]); ?>",
                type: "post",
                data: {comment_notification_option: value},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if ((data === "true")) {
                        $(radios).find(".updated-text").css("display","");
                    } else {
                        //Feedback error
                    }
                }
            });
        });
    });

</script>

<?= $this->Html->css("large-bootstrap-controls.css");?>

<style>

    .options-checkbox {
        margin: 10px 0px;
    }

    .updated-text {
        color: #00df00;
    }

    .comment-notification-radios .custom-control{
        margin: 5px 0px;
    }

    .comment-notification-radios label{
        margin: 5px 0px;
        font-size: 1.25rem;
    }

</style>

<div class="row border-bottom border-gray mt-3">
    <div class="col-12">
        <h3>Event Email Settings</h3>
    </div>
</div>
<div class="form-group mt-3">
    <div class="custom-control custom-checkbox options-checkbox form-control-lg" >
        <input type="checkbox" class="custom-control-input" id="weeklyEmail" <?php if($user->weekly_event_email){ echo "checked";} ?>>
        <label class="custom-control-label" for="weeklyEmail">I want <b>weekly emails</b> about the weekly ongoing events in Yordas</label>
        <small class="updated-text" style="display:none;"><i>Updated!</i></small>
    </div>
    <div class="custom-control custom-checkbox options-checkbox form-control-lg">
        <input type="checkbox" class="custom-control-input" id="perEventEmail" <?php if($user->email_per_event){ echo "checked";} ?>>
        <label class="custom-control-label" for="perEventEmail">I want emails <b>every time</b> a new event is posted</label>
        <small class="updated-text" style="display:none;"><i>Updated!</i></small>
    </div>
    <div class="custom-control custom-checkbox options-checkbox form-control-lg">
        <input type="checkbox" class="custom-control-input" id="autoSubcribe" <?php if($user->auto_post_subscribe){ echo "checked";} ?>>
        <label class="custom-control-label" for="autoSubcribe">I want <b>email notifications when someone comments on my posts</b> <i>(auto subscribes to your own posts)</i></label>
        <small class="updated-text" style="display:none;"><i>Updated!</i></small>
    </div>
</div>

<div class="row border-bottom border-gray mt-3">
    <div class="col-12">
        <h3>Comment Notification Settings</h3>
    </div>
</div>
<div class="form-group mt-3">
    <div class="comment-notification-radios">
        <div class="custom-control custom-radio">
            <input type="radio" id="no-delay" value=0 name="comment-notification" class="custom-control-input" <?= $comment_option1 ?>>
            <label class="custom-control-label" for="no-delay">Receive comment email notifications with no delay</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="10-min-delay" value=1 name="comment-notification" class="custom-control-input" <?= $comment_option2 ?>>
            <label class="custom-control-label" for="10-min-delay">Receive comment email notifications with 10 minute delay (group of comments posted in 10 minutes)</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="day-delay" value=2 name="comment-notification" class="custom-control-input" <?= $comment_option3 ?>>
            <label class="custom-control-label" for="day-delay">Receive comment email notifications with 12 hour delay (group of comments posted in 12 hours)</label>
        </div>
        <small class="updated-text" style="display:none;"><i>Updated!</i></small>
        <i>Note: You will only receive comment notifications on posts you're subscribed to.</i>
    </div>
</div>