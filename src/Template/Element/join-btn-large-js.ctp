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

<script>
    $(function(){
        $(document).on("click",".event .join-large-btn", function(e){
            e.preventDefault();
            var eventId = $(this).closest(".event").attr("data-event-id");
            var joinBtn = $(this);

            $.ajax({
                url: "<?= Router::url(['controller' => 'EventParticipants', 'action' => 'add']); ?>",
                type: "post",
                data: {event_id: eventId},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    if (data === "true") {
                        joinBtn.removeClass("join-large-btn");
                        joinBtn.addClass("leave-large-btn");
                        joinBtn.removeClass("btn-success").addClass("btn-danger");
                        joinBtn.html("Leave Event");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $(document).on("click",".event .leave-large-btn", function(e){
            e.preventDefault();
            var leaveBtn = $(this);
            var eventId = $(this).closest(".event").attr("data-event-id");

            $.ajax({
                url: "<?= Router::url(['controller' => 'EventParticipants', 'action' => 'delete']); ?>",
                type: "post",
                data: {event_id: eventId},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    if (data === "true") {
                        leaveBtn.removeClass("leave-large-btn");
                        leaveBtn.addClass("join-large-btn");
                        leaveBtn.removeClass("btn-danger").addClass("btn-success");
                        leaveBtn.html("Join Event");
                    } else {
                        //Feedback error
                    }
                }
            });
        });
    })
</script>