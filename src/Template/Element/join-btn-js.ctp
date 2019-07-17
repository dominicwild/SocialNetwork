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

    var eventRequests = [];

    function joinBtnToggle(joinBtn){
        joinBtn.removeClass("join-btn");
        joinBtn.addClass("leave-btn");
        joinBtn.removeClass("btn-outline-success").addClass("btn-outline-danger");
        joinBtn.html("Leave");
        var count = $(joinBtn).closest(".card").find(".member-count").html();
        $(joinBtn).closest(".card").find(".member-count").html(+count+1);
    }

    function leaveBtnToggle(leaveBtn){
        leaveBtn.removeClass("leave-btn");
        leaveBtn.addClass("join-btn");
        leaveBtn.removeClass("btn-outline-danger").addClass("btn-outline-success");
        leaveBtn.html("Join");
        var count = $(leaveBtn).closest(".card").find(".member-count").html();
        $(leaveBtn).closest(".card").find(".member-count").html(+count-1);
    }

    $(function(){

        $(document).on("click",".group .join-btn", function(e){
            e.preventDefault();
            var groupId = $(this).closest(".group").attr("data-group-id");
            var joinBtn = $(this);

            $.ajax({
                url: "<?= Router::url(['controller' => 'GroupMembers', 'action' => 'add']); ?>",
                type: "post",
                data: {groupId: groupId},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    if (data === "true") {
                        joinBtnToggle(joinBtn);
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $(document).on("click",".group .leave-btn", function(e){
            e.preventDefault();
            var groupId = $(this).closest(".group").attr("data-group-id");
            var leaveBtn = $(this);

            $.ajax({
                url: "<?= Router::url(['controller' => 'GroupMembers', 'action' => 'delete']); ?>",
                type: "post",
                data: {groupId: groupId},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    if (data === "true") {
                        leaveBtnToggle(leaveBtn);
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $(document).on("click",".event .leave-btn", function(e){
            e.stopPropagation();
            var eventId = $(this).closest(".event").attr("data-event-id");
            var leaveBtn = $(this);

            if(!eventRequests.includes(eventId)) {
                eventRequests.push(eventId);
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
                            leaveBtnToggle(leaveBtn);
                        } else {
                            $("body").before("leave " + data);
                            //Feedback error
                        }
                    },
                    complete: function (data) {
                        var index = eventRequests.indexOf(eventId);
                        if (index !== -1){ eventRequests.splice(index, 1); }
                    }
                });
            }
        });

        $(document).on("click",".event .join-btn", function(e){
            e.stopPropagation();
            var eventId = $(this).closest(".event").attr("data-event-id");
            var joinBtn = $(this);

            if(!eventRequests.includes(eventId)) {
                eventRequests.push(eventId);

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
                            joinBtnToggle(joinBtn);
                        } else {
                            $("body").before("join " + data);
                            //Feedback error
                        }
                    },
                    complete: function (data) {
                        var index = eventRequests.indexOf(eventId);
                        if (index !== -1){ eventRequests.splice(index, 1); }
                    }
                });


            }

        });

    })

</script>