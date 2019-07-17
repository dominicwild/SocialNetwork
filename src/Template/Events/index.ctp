<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 * @var string $calendar_id
 */
use Cake\Routing\Router;

//$this->extend("event_base");
$events = $events->toArray();


?>


<?= $this->Html->css("post")?>
<?= $this->Html->css("cards")?>
<?= $this->element("join-btn-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("join-btn-large-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("event-post-js")?>
<?= $this->element("post-js",["csrfToken" => $_COOKIE["csrfToken"]])?>

<script>

    $(".nav-events").addClass("active");

    function deletePostCallBack(post) {
        var row = $(post).closest(".row");
        var eventId = $(row).find(".event").data("event-id");
        $(row).slideUp(function(){
            $(row).remove();
        });

        var event = $('.event-cards .event').filter("[data-event-id='" + eventId + "']");
        var container = $(event).closest(".event-card-container");
        $(container).slideUp(function(){
            $(container).remove()
        });
    }

    function calendarBtnToggle(e){
        if($("#calendar-view.collapse").length === 1) {
            var btn = e.target;
            if ($(btn).html() === "Hide Calendar") {
                $(btn).html("Show Calendar");
            } else {
                $(btn).html("Hide Calendar");
            }
        }
    }

    $(function(){

        var slideTime = 500;

        $(".calendar-btn").on("click", calendarBtnToggle)

        function hiddenDiv(data){ //Creates div with display none encasing "data"
            return $(document.createElement("div")).html(data).css("display", "none")
        }

        function configurePost(post){
            $(post).find(".contentArea").each(configureContentArea);
            //$(post).find(".poster-name").removeClass("col-9").addClass("col-8");
            //$(post).find(".post-btn-group").removeClass("col-2").addClass("col-4");
            initPost(post);
            post.slideDown(slideTime);
        }
        //

        $(document).on("click", "#create-event-btn", function(){
            window.location.href = "<?= Router::url(["controller" => "Events", "action" => "add"],true);?>";
        })

        $(document).on("click", ".event-cards .event", function(){
            var eventId = $(this).attr("data-event-id");
            if($(".loadedEvent").length != 0){
                var loadedEvent = $(".loadedEvent");
                var loadedId = $(".loadedEvent .event").attr("data-event-id");
                if(eventId == loadedId){
                    loadedEvent.slideUp(slideTime, function(){
                        loadedEvent.remove();
                    });
                    return;
                }
            }
            $.ajax({
                url: "<?= Router::url(['controller' => 'Events', 'action' => "fetchEventPost"]); ?>",
                type: "post",
                data: {event_id: eventId},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (!(data === "")) {
                        var div;
                        if($(".loadedEvent").length == 0) {
                            div = hiddenDiv(data).addClass("slidingEvent");
                            $(div).addClass("loadedEvent");
                            $(".content-container").children().first().before(div); //insert as first child
                            configurePost(div);
                            $(div).find(".event-edit-btn").each(configureEventEditBtn);
                        } else {
                            div = $(".loadedEvent");
                            div.slideUp(slideTime,function(){
                                div.html(data);
                                configurePost(div);
                                $(div).find(".event-edit-btn").each(configureEventEditBtn);
                            });
                        }
                    } else {
                        //Feedback error
                    }
                }
            });
        })

        $(document).on("click","#view-all-events-btn", function(e){
            window.location.href = "<?= Router::url(["controller" => "Events", "action" => "viewAll"],true)?>"
        });

    });

</script>



<?= $this->Html->css("image-overlay.css") ?>

<?php if(isset($link_event)): ?>
<div class="slidingEvent loadedEvent">
    <?php
    if(isset($comment_id)){
        echo $this->element("event-post", ["event" => $link_event, "link_comment_id" => $link_comment_id]);
    } else {
        echo $this->element("event-post", ["event" => $link_event, "highlight" => true]);
    }
    ?>
</div>
<?php endif;?>

<div id="drop-area" style="display:none"></div>

<div class="row border-bottom border-gray mt-3">
    <div class="col-8">
        <h3>Upcoming Events</h3>
    </div>
    <div class="col-4 my-auto d-flex justify-content-end">
        <button type="button" class="btn btn-sm btn-outline-secondary mr-2 calendar-btn" data-toggle="collapse" data-target="#calendar-view" >Hide Calendar</button>
        <div class="btn-group justify-content-center">
            <button type="button" id="create-event-btn" class="btn btn-sm btn-outline-secondary">Create Event</button>
            <button type="button" id="view-all-events-btn" class="btn btn-sm btn-outline-secondary">View All</button>
        </div>
    </div>
</div>
<div class="row mt-3 event-cards">
    <?php foreach($events as $event): ?>
        <?= $this->element("event",["event" => $event]);?>
    <?php endforeach; ?>
    <?php if(count($events) == 0): ?>
        <div class="col-12 text-center">
            <h2 class="text-muted"><i>There are no events. Got any ideas?</i></h2>
        </div>
    <?php endif; ?>
</div>

<div class="row mt-3">
    <div id="calendar-view" class="w-100 collapse show">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FLondon&amp;src=<?= $calendar_id ?>&amp;color=%23A79B8E&amp;showTz=1&amp;mode=MONTH&amp;showCalendars=0&amp;showPrint=0&amp;showTabs=1&amp;showNav=1&amp;showTitle=0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
</div>