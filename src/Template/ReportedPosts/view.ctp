<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\User $users
 * @var \App\Model\Entity\ReportedPost[] $reports
 */
use Cake\Routing\Router;

$this->extend("/Users/admin_base");
$reports = $reports->toArray();
?>

<?= $this->element("post-image-overlay") ?>
<?= $this->Html->css("post")?>
<?= $this->Html->css("reported-view")?>
<?= $this->element("post-js",["csrfToken" => $_COOKIE["csrfToken"]])?>

<script>
    $(".nav-admin").addClass("active");

    function reportViewBtn(e){
        var btn = e.target;
        var report = $(btn).closest(".report");

        if($(btn).hasClass("pressed")){ //Hide the post
            $(btn).removeClass("pressed btn-secondary").addClass("btn-info").css("border-radius","");
            $(btn).html("View Post");
            $(report).find(".reported-post").slideUp();
        } else { //Show the post
            $(btn).addClass("pressed btn-secondary").removeClass("btn-info").css("border-radius","0px");
            $(btn).html("Hide Post");
            if($(report).find(".reported-post").length === 0){
                renderPost(report);
            } else {
                $(report).find(".reported-post").slideDown();
            }
        }
    }

    function renderPost(report){
        var id = $(report).data("reported-post-id");
        console.log("id: " + id);

        $.ajax({
            url: "<?= Router::url(['controller' => 'Posts', 'action' => 'getPost']); ?>",
            type: "post",
            data: {id: id},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
            },
            success: function (data) {
                var reportedPost = $(document.createElement("div")).addClass("reported-post").html(data).css("display", "none");
                $(reportedPost).find(".slidingDiv").first().addClass("p-3");
                initPost(reportedPost);
                $(reportedPost).find(".post").removeClass("mt-3");
                $(report).children().last().after(reportedPost);
                $(reportedPost).slideDown();
            }
        });
    }




    $(function(){
        $(".reported-post-view").on("click", reportViewBtn);

        var stopRender = false;             //Indicates to stop loading more elements
        var renderRequestSent = false;

        $(window).scroll(function () {
            if (!stopRender && !renderRequestSent) {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 4000) {
                    renderRequestSent = true;

                    var lastId = $(".report").last().attr("data-report-id");
                    console.log(lastId);
                    $.ajax({
                        url: "<?= Router::url(['controller' => 'ReportedPosts', 'action' => "loadMoreReportedPosts"]); ?>",
                        type: "post",
                        data: {last_id : lastId},
                        dataType: "html",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', "<?php echo $_COOKIE["csrfToken"] ?>");
                        },
                        success: function (data) {
                            var div = $(document.createElement("div")).html(data).addClass("loadedReports").css("display","none");
                            $(".admin-content").children().last().after(div);
                            $(div).fadeIn();
                            renderRequestSent =false;
                            if($("#admin-end-load").length  > 0){
                                stopRender = true;
                            }
                        },
                        error: function(data){
                            renderRequestSent =false;
                        }
                    });
                }
            }
        });
    });
</script>

<div class="row border-bottom border-gray mt-3">
    <div class="col-12">
        <h3>Reports</h3>
    </div>
</div>



<?php if(sizeof($reports) > 0): ?>
    <?php
    foreach ($reports as $report) {
        echo $this->element("reported_post", ["report" => $report]);
    }
    ?>
<?php else: ?>
    <div class="col-12 text-center mt-3">
        <h2 class="text-muted"><i>There are no reports.</i></h2>
    </div>
<?php endif; ?>

