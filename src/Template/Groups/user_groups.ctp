<?php
/**
 * @var \App\Model\Entity\User $target_user
 * @var \Cake\View\View $this
 */
use Cake\Routing\Router;
?>


<script>
    function loadMoreData(data){
        data.append("post_type", <?= \App\Model\Entity\Post::TYPE_GROUP ?>);
    }
</script>

<?php

if($target_user == $user){
    $title = "Your Groups";
} else {
    $title = $target_user->FirstName . " " . $target_user->LastName . "'s Groups";
}

$this->set("show_activity", false);
$this->set("default_load_more", false);
$this->extend("/Posts/home_base");
?>

<?= $this->Html->css("image-overlay.css") ?>
<?= $this->element("join-btn-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("join-btn-large-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("event-post-js")?>
<?= $this->element("post-image-overlay")?>


<script>

    $(function(){

        $(window).on("resize", function(){
            if($(".my-view-all-groups-btn").attr("data-toggle") == 0) {
                var height = $(".group").height();
                $(".my-groups-container").css("max-height", (height + 10) + "px");

            }
        });

        $(window).trigger("resize");

        $(".my-view-all-groups-btn").on("click", function(e){
            if($(this).attr("data-toggle") == 0){
                $(document).find(".my-groups-container").css("max-height","100%");
                $(this).find(".my-view-all-groups-btn-arrow").css("transform","rotate(180deg)");
                $(this).attr("data-toggle", 1);
            } else {
                var height = $(".group").height();
                $(document).find(".my-groups-container").css("max-height", (height + 25) + "px");
                $(this).find(".my-view-all-groups-btn-arrow").css("transform","rotate(0deg)");
                $(this).attr("data-toggle", 0);
            }
        });

        var stopRender = false;
        var renderRequestSent = false;

        $(window).scroll(function () {
            if (!stopRender && !renderRequestSent) {

                if ($(window).scrollTop() > $(document).height()*0.8) {
                    renderRequestSent = true;

                    var lastPostId = $(".post").last().attr("data-post-id");
                    var lastEventId = $(".event").last().attr("data-event-id");

                    $.ajax({
                        url: "<?= Router::url(['controller' => 'Groups', 'action' => 'loadMoreUserGroup']); ?>",
                        type: "post",
                        data: {last_event_id: lastEventId, last_post_id: lastPostId, id: <?= $_GET["id"] ?>},
                        dataType: "html",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', "<?php echo $_COOKIE["csrfToken"] ?>");
                        },
                        success: function (data) {
                            if (data.length == 2 || data == "") { //Means no more posts to get
                                var endDiv = $(document.createElement("div")).addClass("card text-center text-white bg-info my-3");
                                var endText = $(document.createElement("div")).html("You've reached the beginning of time for the group! There are no more posts to find past this point.");
                                var endImage = $(document.createElement("img")).attr("src", "/img/si-glyph-clock.svg").css("width", "64px").css("height", "64px");
                                var imgDiv = $(document.createElement("div")).append(endImage).addClass("my-2");
                                $(endDiv).append(endText);
                                $(endDiv).append(imgDiv);
                                $(".wall").children().last().after(endDiv);
                                stopRender = true;
                            } else {
                                var div = document.createElement("div");
                                $(div).html(data);
                                initPost(div);
                                $(div).find(".event-edit-btn").each(configureEventEditBtn);
                                $(".wall").children().last().after(div);
                            }

                            renderRequestSent =false;
                        },
                        error: function(data){
                            renderRequestSent =false;
                        }
                    });
                }
            }
        });

    })

</script>

<?= $this->Html->css("user-groups.css")?>


<div class="row border-bottom border-gray mt-3 mx-1">
    <div class="col-9">
        <h3 class="mb-1"><?= $title ?></h3>
    </div>
</div>

<div class="row mt-3 my-groups-container">
    <?php foreach ($groups as $group): ?>
        <div class="col-2 px-2 d-flex">
            <?= $this->element("group", ["group" => $group]); ?>
        </div>
    <?php endforeach; ?>
</div>
<?php if(count($groups) > 6):?>
<div class="row mt-3">
    <div class="col-12">
        <button class="btn-block btn btn-outline-info my-view-all-groups-btn" data-toggle=0>
                <img class="my-view-all-groups-btn-arrow" src="/img/Arrow-down.svg">
                View All Groups
                <img class="my-view-all-groups-btn-arrow" src="/img/Arrow-down.svg">
        </button>
    </div>
</div>
<?php endif; ?>
<?php if(count($groups) == 0):?>
    <div class="col-12 text-center">
        <h2 class="text-muted"><i>It appears this user has no groups.</i></h2>
    </div>
<?php endif; ?>
<div class="row border-bottom border-gray mt-3 mx-1">
    <div class="col-9">
        <h3 class="mb-1">Recent Group Posts & Events</h3>
    </div>
</div>
<div class="row">
    <div class="col-12">
    <?php
        $this->set("group_posts",$group_posts);
        $this->set("events", $events);
        echo $this->element("group-content");
    ?>
        <?php if(count($group_posts) == 0 && count($events) == 0):?>
            <div class="col-12 text-center my-4">
                <h2 class="text-muted"><i>There is no group activity.</i></h2>
            </div>
        <?php endif; ?>
    </div>
</div>
