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
    function configurePost(index,post){
        //$(post).find(".commentArea").each(configureCommentArea);
        $(post).find(".poster-name").removeClass("col-9").addClass("col-9");
        //$(post).find(".post-btn-group").removeClass("col-2").addClass("col-3");
    }

    function deletePostCallBack(post){

        var eventPost = $(post).closest(".event-post");

        eventPost.slideUp(slideTime, function(){
            eventPost.remove();
        })
    }

    function configureEventEditBtn(index,btn){
        var event = $(btn).closest(".event");
        $(event).css("border-radius","10px 10px .25em .25em");
        $(event).find(".event-image").css("border-radius",0);
        var eventId = $(event).attr("data-event-id");
    }

    $(function(){
        $(".post").each(configurePost);

        $(".event-edit-btn").each(configureEventEditBtn);
    });

</script>