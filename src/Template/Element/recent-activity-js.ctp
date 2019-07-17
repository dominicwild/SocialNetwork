<?php
/**
 * @var \App\Model\Entity\Activity $activity
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 *
 */
use Cake\Routing\Router;
?>

<script>

    var pageNum = 1;

    function updatePageNum(increment){
        pageNum += increment;
        $(".activity-page-number").html(pageNum);
    }

    $(function(){

        var nextPageProcessing = false;

        function updateActivity(direction, event){

            if(nextPageProcessing == false) {
                nextPageProcessing = true;
                var refId;
                var btn = event;
                if (direction > 0) {
                    refId = $(".recent-activity ul li").last().attr("data-activity-id");
                } else {
                    refId = $(".recent-activity ul li").first().attr("data-activity-id");
                }

                var ids = [];
                $(".activity-item").each(function (index, activity) {
                    ids.push($(activity).attr("data-activity-id"))
                });

                $.ajax({
                    url: "<?= Router::url(['controller' => 'Activity', 'action' => 'scrollActivity']); ?>",
                    type: "post",
                    data: {id: refId, exclude_ids: ids, direction: direction},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                    },
                    success: function (data) {
                        if (!(data === "")) {
                            $(".recent-activity ul").html(data);
                            $(".activity-arrow-left").prop("disabled", false);
                            $(".activity-arrow-right").prop("disabled", false);
                            updatePageNum(direction);
                        } else {
                            $(btn).prop("disabled", true);
                        }
                        nextPageProcessing = false;
                    },
                    error: function(data){
                        nextPageProcessing = false;
                    }
                });
            }
        }

        $(".activity-arrow-right").on("click", function(e){
            updateActivity(1,this)
        });

        $(".activity-arrow-left").on("click", function(e){
            updateActivity(-1,this)
        });

    });

</script>