<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;

if(!isset($show_activity)){
    $show_activity = true;
}
if(!isset($default_load_more)){
    $default_load_more = true;
}
?>

<script>

    $(".nav-home").addClass("active");
    $(".container").removeClass("container").addClass("container-fluid pr-4");
    <?php if($default_load_more): ?>
    $(function(){

        var stopRender = false;
        var renderRequestSent = false;

        $(window).scroll(function () {
            if (!stopRender && !renderRequestSent) {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 4000) {
                    renderRequestSent = true;

                    var lastId = $(".post").last().attr("data-post-id");
                    var data = new FormData();

                    data.append("last_id", lastId);

                    if(typeof loadMoreData === "function"){
                        loadMoreData(data);
                    }

                    $.ajax({
                        url: "<?= Router::url(['controller' => 'Posts', 'action' => "loadMorePosts"]); ?>",
                        type: "post",
                        data: data,
                        contentType: false,
                        processData: false,
                        dataType: "html",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', "<?php echo $_COOKIE["csrfToken"] ?>");
                        },
                        success: function (data) {

                            if (data.length == 2) { //Means no more posts to get
                                var endDiv = $(document.createElement("div")).addClass("card text-center text-white bg-info my-3");
                                var endText = $(document.createElement("div")).html("You've reached the beginning of time! There are no more posts to find past this point.");
                                var endImage = $(document.createElement("img")).attr("src", "/img/si-glyph-clock.svg").css("width", "64px").css("height", "64px");
                                var imgDiv = $(document.createElement("div")).append(endImage).addClass("my-2");
                                $(endDiv).append(endText);
                                $(endDiv).append(imgDiv);
                                $(".wall").children().last().after(endDiv);
                                stopRender = true;
                            } else {
                                var div = $(document.createElement("div")).html(data);
                                $(".wall").children().last().after(div);
                                // $(div).find(".post-card-body-content").each(readMoreCheck);
                                // $(div).find(".read-more-btn").on("click", readToggle);
                                initPost(div);
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
    });

    <?php endif; ?>
</script>


<?= $this->element("post-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->Html->css("post")?>
<?= $this->Html->css("cards")?>

<div class="row">
    <?= $this->element("home-side-bar")?>
    <?php if($show_activity): ?>
        <div class="col-7 p-0 wall pl-3">
           <?= $this->fetch("content")?>
        </div>
        <div class="col-3 mt-3 pr-0">
            <?php $this->set("activity",$activity); ?>
            <?= $this->element("recent-activity")?>
        </div>
    <?php else: ?>
        <div class="col-10 p-0 wall pl-3 pr-3">
            <?= $this->fetch("content")?>
        </div>
    <?php endif; ?>
</div>