<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Group $group
 */

use Cake\Routing\Router;
use App\Model\Entity\User;
use App\View\Helper\MiscellaneousHelper;

?>


<script>

    function textAreaCss(textArea = this){
        $(textArea).css("height", this.scrollHeight + "px");
        $(textArea).css("overflow-y", "hidden");
    }

    function textAreaAutoExtend(limit){
        return function() {
            if (this.scrollHeight < limit) {
                $(this).css("overflow-y", "hidden");
                $(this).css("height", "auto");
                $(this).css("height", this.scrollHeight);
            } else {
                $(this).css("overflow-y", "scroll");
            }
        }
    }

    function createTextArea(limit){
        var textArea = $(document.createElement("textarea")).addClass("form-control").addClass("contentArea");
        if(limit){
            $(textArea).css("height", this.scrollHeight + "px");
            $(textArea).css("overflow-y", "hidden");
            $(textArea).on("input", textAreaAutoExtend(limit));
            return textArea;
        } else {
            return $(textArea).attr("rows",1);
        }
    }

    function changeBtnGroupState(btnGroup){
        var slideTime = 200;
        var state = btnGroup.attr("data-state");

        if(state === "edit"){
            //Change state to confirm/cancel and place in text areas
            btnGroup.attr("data-state","confirm-cancel");

            $(btnGroup).closest(".slidingDiv").slideToggle(slideTime, function(){
                $(btnGroup).find(".confirmBtn").removeClass("btn-primary").addClass("btn-success").css("border-radius","").find("img").attr("src","/img/check.svg");
                $(btnGroup).find(".cancelBtn").css("display","");
                $(btnGroup).closest(".slidingDiv").slideToggle(slideTime);
            });

        } else {
            btnGroup.attr("data-state","edit");

            $(btnGroup).closest(".slidingDiv").slideToggle(slideTime, function(){
                $(btnGroup).find(".confirmBtn").removeClass("btn-success").addClass("btn-primary").css("border-radius",".25em").find("img").attr("src","/img/si-glyph-document-edit.svg");
                $(btnGroup).find(".cancelBtn").css("display","none");
                $(btnGroup).closest(".slidingDiv").slideToggle(slideTime);
            });

        }
    }

    function addPostData(data){
        data.append("_group_id",<?=$_GET["id"]?>);
        return data;
    }

    function deleteGroup(e){
        if(confirm("You are about to delete a group and all its contents. Posts, Events, Images, Polls etc. will all be deleted. \n\nIs this OK?")){
            $.ajax({
                url: "<?= Router::url(['controller' => 'Groups', 'action' => 'delete']); ?>",
                type: "post",
                data: {id: <?= $group->id ?>},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (data === "true") {
                        window.location = "<?= Router::url(["controller" => "Groups", "action" => "index"])?>";
                    } else {
                        window.location = "<?= Router::url(["controller" => "Groups", "action" => "view", "id" => $_GET["id"]])?>";
                    }
                }
            });
        }
    }

    $(function () {

        $(".group-delete-btn").on("click",deleteGroup);

        $(document).on("click", ".group-btn-group .editBtn", function(){
            var btnGroup = $(this).closest(".btn-group");
            var state = btnGroup.attr("data-state");

            if(state === "edit"){ //Put text areas into editable areas.
                changeBtnGroupState(btnGroup);
                innerHTMLTextAreaEmoji($(".description"),createTextArea(Infinity));
            } else { //Send edit to back-end to save

                var descriptionContent = $(".description textarea").val();
                var originalDescriptionContent = $(".description textarea").attr("data-content");

                if(descriptionContent === ""){
                    toastr["warning"]("Group must have some description.");
                }

                if(!(descriptionContent === originalDescriptionContent || descriptionContent === "")) {
                    $.ajax({
                        url: "<?= Router::url(['controller' => 'Groups', 'action' => 'edit']); ?>",
                        type: "post",
                        data: {description: descriptionContent, id: <?= $group->id ?>},
                        dataType: "html",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                        },
                        success: function (data) {
                            changeBtnGroupState(btnGroup);
                            $(".description").html(data);
                            $(".group-description-by").html("<?= "Description by: " . $user->FirstName . " " . $user->LastName?>");
                        }
                    });
                } else {
                    $(".cancelBtn").click();
                }
            }

        });

        $(document).on("click", ".group-btn-group .cancelBtn", function(){
            var btnGroup = $(this).closest(".btn-group");
            var state = btnGroup.attr("data-state");

            if(state === "edit"){ //Put text areas into editable areas.
                changeBtnGroupState(btnGroup);
            } else {
                var descriptionContent = $(".description textarea").attr("data-content");
                $(".description").html(emojione.toImage(descriptionContent));
                changeBtnGroupState(btnGroup);
            }
        });

        $(document).on("click", "#change-picture", function(){
            $("#image-upload").click();
        });

        $(document).on("click",".group-info .join-large-btn", function(e){
            var joinBtn = $(this);

            $.ajax({
                url: "<?= Router::url(['controller' => 'GroupMembers', 'action' => 'add']); ?>",
                type: "post",
                data: {groupId: <?= $group->id ?>},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (data === "true") {
                        joinBtn.removeClass("join-large-btn");
                        joinBtn.addClass("leave-large-btn");
                        joinBtn.removeClass("btn-success").addClass("btn-danger");
                        joinBtn.html("Leave");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        $(document).on("click",".group-info .leave-large-btn", function(e){
            var leaveBtn = $(this);

            $.ajax({
                url: "<?= Router::url(['controller' => 'GroupMembers', 'action' => 'delete']); ?>",
                type: "post",
                data: {groupId: <?= $group->id ?>},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (data === "true") {
                        leaveBtn.removeClass("leave-large-btn");
                        leaveBtn.addClass("join-large-btn");
                        leaveBtn.removeClass("btn-danger").addClass("btn-success");
                        leaveBtn.html("Join");
                    } else {
                        //Feedback error
                    }
                }
            });
        });

        var stopRender = false;
        var renderRequestSent = false;

        $(window).scroll(function () {
            if (!stopRender && !renderRequestSent) {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 4000) {
                    renderRequestSent = true;

                    var lastPostId = $(".post").last().attr("data-post-id");
                    if(lastPostId > 0) {
                        $.ajax({
                            url: "<?= Router::url(['controller' => 'Groups', 'action' => 'loadMore']); ?>",
                            type: "post",
                            data: {last_post_id: lastPostId, group_id: <?= $_GET["id"] ?>},
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
                                    $(".container").children().last().after(endDiv);
                                    stopRender = true;
                                } else {
                                    var div = document.createElement("div");
                                    $(div).html(data);
                                    initPost(div);
                                    $(div).find(".event-edit-btn").each(configureEventEditBtn);
                                    $(".container").children().last().after(div);
                                }
                                renderRequestSent = false;
                            },
                            error: function (data) {
                                renderRequestSent = false;
                            }
                        });
                    }
                }
            }
        });

    });

</script>

<?= $this->Html->css("image-overlay.css") ?>
<?= $this->Html->css("post")?>
<?= $this->element("join-btn-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("join-btn-large-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("event-post-js")?>
<?= $this->element("post-js",["csrfToken" => $_COOKIE["csrfToken"]])?>

<?= $this->Html->css("group-view.css") ?>

<?php

if(isset($group->image_by->FirstName) || isset($group->image_by->LastName)){
    $image_by = $group->image_by->FirstName . " " . $group->image_by->LastName;
} else {
    $image_by = "Unknown";
}

if(isset($group->description_by->FirstName) || isset($group->description_by->LastName)){
    $description_by = $group->description_by->FirstName . " " . $group->description_by->LastName;
} else {
    $description_by = "Unknown";
}

?>

<div class="row mt-3">

        <div class="col-4 group-left">
            <div class="image-overlay" id="change-picture">
                <div id="black-overlay" style="background-color:black;width:100%;height: 100%;position: absolute;z-index: -1;"></div>
                <img class="img-fluid shadow overlayed-image"src="<?= $this->Miscellaneous->getImage($group->image) ?>"/>
                <div class="middle-overlay">
                    <img src="/img/file-media.svg" style="width:100%;height:100%">
                </div>
            </div>
            <div class="col-12 pl-0 text-center">
                <small class="text-muted group-image-by"><?= "Image by: " . $this->Miscellaneous->processContent($image_by) ?></small>
            </div>
            <div class="col-12 pl-0 text-center mt-2">
                <?php if($group->user_in_group):?>
                    <button type="button" class="btn btn-danger animated leave-large-btn">Leave</button>
                <?php else: ?>
                    <button type="button" class="btn btn-success animated join-large-btn">Join</button>
                <?php endif; ?>
            </div>
        </div>
    <div class="col-8">
        <div class="row border-bottom border-gray p-0">
            <h2 class="m-0 col-8 pt-2"><?= $this->Miscellaneous->processContent($group->name) ?></h2>
            <div class="col-4 text-center d-flex justify-content-end align-items-end">
                <div class="slidingDiv group-btn-group">
                    <span class="btn-group m-0 p-0 col-12 pb-1 animated" data-state="edit" role="group">
                        <button type="button" class="btn btn-group-sm py-1 btn-primary animated editBtn confirmBtn" style="border-radius: .25rem;">
                            <img src="/img/si-glyph-document-edit.svg">
                        </button>
                        <button type="button" class="btn btn-danger py-1 cancelBtn p-0" style="display: none;">
                            <img src="/img/x.svg">
                        </button>
                    </span>
                </div>
                <?php if($user->Permissions == 100): ?>
                <span class="pb-1 delete-btn-container">
                    <button class="btn btn-danger group-delete-btn ml-2 py-1" title="Delete Group">
                        <img src="/img/si-glyph-trash.svg">
                    </button>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 mt-1 p-0 group-right">
            <span class="description"><?= $this->Miscellaneous->processContent($group->description) ?></span>
            <div class="col-12 pl-0">
                <small class="text-muted group-description-by"><?= "Description by: " . $this->Miscellaneous->processContent($description_by) ?></small>
            </div>
        </div>
        <div class="row mt-3 border-bottom border-gray">
            <h3 class="mb-1 pl-3">Members</h3>
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <?php foreach($group_members as $member){
                    echo $this->element("profile-image-link",["user" => $member->user]);
                }   ?>
            </div>
        </div>

        <?= $this->Form->create(false, ["enctype" => "multipart/form-data", "method" => "POST", "controller" => "Groups","action" => "change-group-picture"]) ?>
        <?= $this->Form->file("file", ["id" => "image-upload", "onchange" => "form.submit();", "name" => "file", "style" => "display:none;"]) ?>
        <?= $this->Form->hidden("id", ["value" => $_GET["id"]]) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
<div class="row">
    <div class="col-12 p-0">
        <?=$this->element("post-image-overlay")?>
        <?=$this->element("post-controls")?>
    </div>
</div>
<div class="row">
    <?php

    //Generate highlighted post/comment
    if (isset($link_post)) {
        echo "<div class=\"col-12 p-0 border-bottom border-gray pb-1\">";
        if (isset($link_comment_id)) {
            echo $this->element("post" , ["post" => $link_post, "link_comment_id" => $link_comment_id]);
        } else {
            echo $this->element("post" , ["post" => $link_post, "highlight" => true]);
        }
        echo "</div>";
    }

    $this->set("group_posts",$group_posts);
    $this->set("events", $group->events);
    echo $this->element("group-content");
    ?>
</div>