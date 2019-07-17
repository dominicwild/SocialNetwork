<?php
/**
 * @var \Cake\View\View $this
 */

use Cake\Routing\Router;
?>

<script>

    function voteSubmit(e) {
        e.stopPropagation();

        var btn = e.target;
        var voteContainer = $(btn).closest(".poll-display-container");
        var voteForm = $(voteContainer).find(".poll-vote-form")[0];

        var data = new FormData(voteForm);

        $.ajax({
            url: "<?= Router::url(['controller' => 'PollVotes', 'action' => 'add']); ?>",
            type: "post",
            data: data,
            contentType: false,
            processData: false,
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            success: function (data) {
                if(data !== ""){
                    switchToViewVotersBtn(btn);
                    $(".poll-redo-container").css("display","initial");
                    $(voteForm).html(data);
                    $(voteContainer).find(".poll-add-option-container").css("display","none");
                    $(voteContainer).find(".poll-add-option-input").remove();
                    updateTotalVotes(voteContainer);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //Give some feedback on error
            }
        });

    }

    function switchToViewVotersBtn(btn){
        $(btn).off("click", voteSubmit);
        $(btn).attr("title", "View Voters")
        $(btn).addClass("btn-secondary").removeClass("btn-info");
        $(btn).html("View Voters");
        $(btn).on("click", viewVoters);
        $(btn).data("toggle",0);
        $(btn).closest(".poll-display-container").removeClass("hover-effect");
    }

    function switchToVoteBtn(btn){
        $(btn).closest(".poll-display-container").addClass("hover-effect");
        $(btn).attr("title", "Vote")
        $(btn).off("click", viewVoters);
        $(btn).html("Vote");
        $(btn).removeClass("btn-secondary").addClass("btn-info");
        $(btn).on("click", voteSubmit);
    }

    function viewVoters(e) {
        e.stopPropagation();

        var btn = e.target;
        var voteContainer = $(btn).closest(".poll-display-container").find(".poll-vote-form")[0];

        if($(btn).data("toggle") === 0) {
            $(voteContainer).find(".poll-vote-voters").css("display", "flex");
            $(voteContainer).find(".poll-vote-outcome-container").addClass("separator");
            $(btn).html("Hide Voters");
            $(btn).data("toggle",1);
        } else {
            $(voteContainer).find(".poll-vote-voters").css("display", "none");
            $(voteContainer).find(".poll-vote-outcome-container").removeClass("separator");
            $(btn).html("View Voters");
            $(btn).data("toggle",0);
        }
    }

    function initPoll(post){
        $(post).find(".poll-vote-btn").on("click", voteSubmit);
        $(post).find(".poll-view-voters-btn").on("click", viewVoters);
        $(post).find(".poll-redo-btn").on("click", redoVote);
        // $(post).find(".poll-view-voters-btn").closest(".poll-display-container").removeClass("hover-effect");
        $(post).find(".poll-add-option-btn").on("click", addOptionControl);
        $(post).find(".poll-btn-remove-option").on("click", removeOption);
        $(post).find(".poll-top").on("click",toggleShow);
    }

    function toggleShow(e) {
        var pollTop = $(e.target).closest(".poll-top");
        var pollBottom = $(pollTop).closest(".poll-display-container").find(".poll-bottom");
        if($(pollTop).data("toggle") === 0){
            pollBottom.slideUp();
            $(pollTop).find(".poll-arrow-down").css("transform","rotate(0deg)");
            $(pollTop).find(".poll-top-btn button").fadeOut();
            $(pollTop).attr("title","View Poll");
            $(pollTop).closest(".poll-display-container").addClass("hover-effect");
            $(pollTop).data("toggle",1);
        } else {
            pollBottom.slideDown();
            $(pollTop).find(".poll-top-btn button").fadeIn();
            $(pollTop).attr("title","Hide Poll");
            $(pollTop).closest(".poll-display-container").removeClass("hover-effect");
            $(pollTop).find(".poll-arrow-down").css("transform","rotate(180deg)");
            $(pollTop).data("toggle",0);
        }

    }

    function removeOption(e){

        var btn = e.target;
        var container = $(btn).closest(".poll-option-container");
        var id = $(container).find("input").val();

        $.ajax({
            url: "<?= Router::url(['controller' => 'PollOptions', 'action' => 'delete']); ?>",
            type: "post",
            data: {id: id},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            success: function (data) {
                if(data !== ""){
                   $(container).fadeOut(500, function(){
                       $(container).remove();
                   });
                } else {
                    toastr["error"]("Option cannot be removed. Someone may have voted on it.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Option cannot be removed. Someone may have voted on it.");
            }
        });
    }

    function redoVote(e){

        var voteContainer = $(e.target).closest(".poll-display-container");
        var btn = $(voteContainer).find(".poll-top-btn button");
        var id = $(voteContainer).data("id");



        $.ajax({
            url: "<?= Router::url(['controller' => 'PollVotes', 'action' => 'resetVote']); ?>",
            type: "post",
            data: {id: id},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            success: function (data) {
                if(data !== ""){
                    switchToVoteBtn(btn);
                    $(voteContainer).find(".poll-redo-container").css("display","none");
                    $(voteContainer).find(".poll-add-option-container").css("display","");
                    $(voteContainer).find(".poll-vote-form").html(data);
                    initPoll($(voteContainer).find(".poll-vote-form"));
                    updateTotalVotes(voteContainer);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //Give some feedback on error
            }
        });
    }

    function updateTotalVotes(voteContainer){
        var id = $(voteContainer).data("id");
        $.ajax({
            url: "<?= Router::url(['controller' => 'Polls', 'action' => 'totalVotes']); ?>",
            type: "post",
            data: {id: id},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            success: function (data) {
                if(data !== ""){

                    $(voteContainer).find(".poll-info-votes").html(data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //Give some feedback on error
            }
        });
    }

    function addOptionControl(e){
        var btn = e.target;
        var optionControl = $("#poll-add-option-input-clone").clone();
        $(optionControl).attr("id", "");
        $(optionControl).attr("title", "Add Option");
        $(optionControl).removeClass("cloneable");
        $(optionControl).find(".poll-btn-option-add").on("click", addOption);
        $(btn).closest(".poll-add-option-container").before(optionControl);
    }

    function addOption(e){
        var btn = e.target;
        var optionText = $(btn).closest(".poll-add-option-input").find("input[type=text]").val();
        var pollContainer = $(btn).closest(".poll-display-container");
        var pollId = $(pollContainer).data("id");
        if(optionText !== "") {
            $.ajax({
                url: "<?= Router::url(['controller' => 'PollOptions', 'action' => 'add']); ?>",
                type: "post",
                data: {poll_id: pollId, option_name: optionText},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {

                    if (data !== "") {
                        $(pollContainer).find(".poll-vote-form").children().last().after(data);
                        $(pollContainer).find(".poll-vote-form").children().last().find(".poll-btn-remove-option").on("click", removeOption);
                        $(btn).closest(".poll-add-option-input").remove();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //Give some feedback on error
                }
            });
        } else {
            toastr["warning"]("Option must contain some text.");
        }
    }

    $(function(){

        initPoll($("body"));

    });

</script>