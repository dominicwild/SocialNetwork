<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 */
use Cake\Routing\Router;

$events = $events->toArray();
?>



<script>
    var csrfToken;

    <?php $this->start("append-post-js") ?>
    var stopRender = false;
    var renderRequestSent = false;

    $(window).scroll(function () {
        if (!stopRender && !renderRequestSent) {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 4000) {
                renderRequestSent = true;
                var eventId = $(".event").last().attr("data-event-id"); //Last loaded event
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Events', 'action' => 'loadMore']); ?>",
                    type: "post",
                    data: {id: eventId},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        if (data.length == 0) { //Means no more posts to get
                            var endDiv = $(document.createElement("div")).addClass("card text-center text-white bg-info my-3");
                            var endText = $(document.createElement("div")).html("You've reached the beginning of time for the events! There are no more posts to find past this point.");
                            var endImage = $(document.createElement("img")).attr("src", "/img/si-glyph-clock.svg").css("width", "64px").css("height", "64px");
                            var imgDiv = $(document.createElement("div")).append(endImage).addClass("my-2");
                            $(endDiv).append(endText);
                            $(endDiv).append(imgDiv);
                            $(".container").children().last().after(endDiv);
                            stopRender = true;
                        } else {
                            var div = document.createElement("div");
                            $(div).html(data);
                            initPost(div);
                            $(".container").children().last().after(div);
                            $(div).find(".event-edit-btn").each(configureEventEditBtn);
                        }
                        renderRequestSent = false;
                    },
                    error: function (data) {
                        renderRequestSent = false;
                    }
                });
            }
        }
    });

    <?php $this->end("append-post-js") ?>

</script>

<?= $this->Html->css("post")?>
<?= $this->element("join-btn-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("post-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("event-post-js")?>
<?= $this->element("join-btn-large-js",["csrfToken" => $_COOKIE["csrfToken"]])?>

<style>
    .participant-image {
        height: 50px;
        width: 95%;
        margin-top: 2px;
    }
    .event-post-join-leave-btn {
        width: 65%;
    }

    .event-image {
        height: 144px;
        width: 100%;
        display: block;
        /*border-radius: 0;*/
        border-radius: .25em .25em 0 0;
    }

    .card-body .btn-group {
        width: 25%;
    }
</style>

    <div class="row border-bottom border-gray mt-3">
        <div class="col-12">
            <h3>All Events</h3>
        </div>
    </div>


<?php

$this->set("limit",3);
foreach($events as $event){
    echo "<div class='border-bottom border-gray pb-1 event-post'>";
    $this->set("event",$event);
    echo $this->element("event-post");
    echo "</div>";
}
?>




<?php if(count($events) == 0): ?>
    <div class="col-12 text-center">
        <h2 class="text-muted"><i>There are no events. Got any ideas?</i></h2>
    </div>
<?php endif; ?>

