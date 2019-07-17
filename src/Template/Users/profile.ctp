<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var User $user
 * @var User $logged_user
 */

use Cake\Routing\Router;
use App\Model\Entity\User;
use App\View\Helper\MiscellaneousHelper;

?>

<?php

if($this->getRequest()->getSession()->read("Auth.User.id") == $_GET["id"]){
    $deleteConfirmText = "You are about to delete your account and all your associated content. This means all posts, polls, images, events etc. will be deleted that were made by you.";
} else {
    $deleteConfirmText = "You are about to delete a user and all their associated content. This means all posts, polls, images, events etc. will be deleted that were made by this user.";
}

$owner = $logged_user->id == $user->id;


$change_image_text = $owner == true ? "Change Profile Image" : "";


?>


<?= $this->element("post-js"); ?>

<script>

    <?php if($owner):?>
        $(".nav-profile-link").addClass("active");
    <?php endif; ?>

    function createTextArea(limit = Infinity){
        var textArea = $(document.createElement("textarea")).addClass("form-control").addClass("contentArea");
        return $(textArea).each(textAreaCss).on("input", textAreaAutoExtend(Infinity));
    }

    function createDepartmentSelectionBox(){
        selection = document.createElement("select");
        $(selection).addClass("form-control").addClass("mr-sm-2");
        option = document.createElement("option");
    }

    function createSelection(optionValue){
        return $(document.createElement("option")).attr("value",optionValue).html(optionValue)
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

            //innerHTMLTextArea($(".department td"),createTextArea(Infinity));

            var departmentSpan = $(".department .department-content");
            var departmentContent = departmentSpan.html();
            departmentSpan.html("");
            var selected = 0;
            var options = $(".department .department-selection option");
            for(var i = 0;i<options.length;i++){
                if(options[i].text === departmentContent){
                    selected = i;
                    break;
                }
            }
            $(".department .department-selection")[0].selectedIndex = selected.toString();
            $(".department .department-selection").attr("data-content",departmentContent).css("display","");

            innerHTMLTextArea($(".first-name td"),createTextArea(Infinity));
            innerHTMLTextArea($(".last-name td"),createTextArea(Infinity));
            innerHTMLTextArea($(".role td"),createTextArea(Infinity));
            innerHTMLTextArea($(".gender td"),createTextArea(Infinity));
            innerHTMLTextAreaEmoji($(".status p"),createTextArea(Infinity));
            innerHTMLTextAreaEmoji($(".about-me p"),createTextArea(Infinity));

        } else {
            btnGroup.attr("data-state","edit");

            $(btnGroup).closest(".slidingDiv").slideToggle(slideTime, function(){
                $(btnGroup).find(".confirmBtn").removeClass("btn-success").addClass("btn-primary").css("border-radius",".25em").find("img").attr("src","/img/si-glyph-document-edit.svg");
                $(btnGroup).find(".cancelBtn").css("display","none");
                $(btnGroup).closest(".slidingDiv").slideToggle(slideTime);
            });

        }
    }

    function innerHTMLTextArea(element, textArea){
        var content = element.html();
        element.html(textArea.val(content).attr("data-content",content));
        textArea.trigger("input");
    }

    function deleteUser(e){

        if(confirm("<?= $deleteConfirmText ?> \n\nIs this OK?" )){
            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => 'delete']); ?>",
                type: "post",
                data: {id: <?= $_GET["id"] ?>},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (data === "true") {
                        window.location = "<?= Router::url(["controller" => "Users", "action" => "userList"])?>"
                    } else {
                        window.location = "<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $_GET["id"]])?>"
                    }
                }
            });
        }
    }

    $(function () {

        $(".user-delete-btn").on("click", deleteUser);

        $(document).on("click", ".editBtn.profile-btn", function(){
            var btnGroup = $(this).closest(".btn-group");
            var state = btnGroup.attr("data-state");

            if(state === "edit"){ //Put text areas into editable areas.
                changeBtnGroupState(btnGroup);
            } else { //Send edit to back-end to save

                var departmentSelection = $(".department .department-selection")[0];

                var department = departmentSelection.options[departmentSelection.selectedIndex].text;
                var role = $(".role textarea").val();
                var gender = $(".gender textarea").val();
                var status = $(".status textarea").val();
                var about_me = $(".about-me textarea").val();
                var firstName = $(".first-name textarea").val();
                var lastName = $(".last-name textarea").val();

                if(firstName === ""){
                    toastr["warning"]("You must have a first name. First name not changed.");
                    firstName = $(".first-name textarea").data("content");
                }

                $.ajax({
                    url: "<?= Router::url(['controller' => 'Users', 'action' => 'editProfile']); ?>",
                    type: "post",
                    data: {department: department, role: role,gender:gender,status:status,about_me:about_me, FirstName:firstName, LastName: lastName,id:<?= $_GET["id"] ?>},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                    },
                    success: function (data) {
                        if (data === "true") {
                            $(".department .department-content").html(department);
                            $(".department .department-selection").css("display","none");
                            $(".role td").html(role);
                            $(".first-name td").html(firstName);
                            $(".last-name td").html(lastName);
                            $(".gender td").html(gender);
                            $(".status p").html(emojione.toImage(status));
                            $(".about-me p").html(emojione.toImage(about_me));
                            changeBtnGroupState(btnGroup);
                        } else {
                            //Feedback error
                        }
                    }
                });
            }

        });

        $(document).on("click", ".cancelBtn", function(){
            var btnGroup = $(this).closest(".btn-group");
            var state = btnGroup.attr("data-state");


            if(state === "edit"){ //Put text areas into editable areas.
                changeBtnGroupState(btnGroup);
            } else { //Restore previous content
                var department = $(".department .department-selection").attr("data-content");
                var departmentSelection = $(".department .department-selection");
                var departmentContent = $(".department .department-content");
                departmentSelection.css("display","none");
                departmentContent.html(department);

                var role = $(".role textarea").attr("data-content");
                var gender = $(".gender textarea").attr("data-content");
                var status = $(".status textarea").attr("data-content");
                var about_me = $(".about-me textarea").attr("data-content");
                var firstName = $(".first-name textarea").attr("data-content");
                var lastName = $(".last-name textarea").attr("data-content");

                $(".first-name td").html(firstName);
                $(".last-name td").html(lastName);
                $(".role td").html(role);
                $(".gender td").html(gender);
                $(".status p").html(emojione.toImage(status));
                $(".about-me p").html(emojione.toImage(about_me));
                changeBtnGroupState(btnGroup);
            }
        });

        $(document).on("click", "#change-picture", function(){
            $("#profile-image-upload").click();
        });

    });

</script>

<?= $this->Html->css("profile.css")?>

<?= $this->Html->css("image-overlay.css") ?>

<div class="row border-bottom border-gray profile-header mt-3">
    <div class="col-9 d-flex">
        <h3 class="mt-auto mb-1"><?= $user->FirstName . " " . $user->LastName ?></h3>
    </div>
    <div class="col-3 my-auto p-0">
        <?php if($this->getRequest()->getSession()->read("Auth.User.Permissions") == 100 || $this->getRequest()->getSession()->read("Auth.User.id") == $_GET["id"]): ?>
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-danger profile-btn py-1 ml-2 mb-1 user-delete-btn">
                    <img src="/img/si-glyph-trash.svg">
                </button>
                <input type="hidden" value="<?= $user->id ?>" name="id">
            </div>
        <?php endif; ?>
        <?php if($this->getRequest()->getSession()->read("Auth.User.id") == $_GET["id"]): ?>
        <div class="slidingDiv">
            <span class="btn-group m-0 p-0 float-right profile-edit-btn-group animated" data-state="edit" role="group">
                <button type="button" class="btn btn-group-sm btn-primary py-1 animated profile-btn editBtn confirmBtn mb-1" style="border-radius: 0.25em;">
                    <img src="/img/si-glyph-document-edit.svg">
                </button>
                <button type="button" class="btn btn-danger profile-btn py-1 animated cancelBtn mb-1" style="display: none;">
                    <img src="/img/x.svg">
                </button>
            </span>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-4 p-0">
        <div class="col-12">
            <div class="image-overlay d-flex align-items-center justify-content-center" id="change-picture">
                <?php if($owner): ?>
                    <div id="black-overlay" style="background-color:black;width:100%;height: 100%;position: absolute;z-index: -1;" title="Change Profile Image"></div>
                <?php endif; ?>
                <img class="img-fluid shadow overlayed-image" title="<?= $change_image_text ?>" src="<?= $this->Miscellaneous->getImage($user->profile_image) ?>"/>
                    <div class="middle-overlay">
                        <img src="/img/file-media.svg" style="width:100%;height:100%">
                    </div>
            </div>
        </div>
        <?php if($_SESSION["Auth"]["User"]["id"] == $_GET["id"]): //Authed user controls ?>
            <?= $this->Form->create(false,["enctype" => "multipart/form-data","method" => "POST","url" =>  "/users/change-profile-picture"]) ?>
            <?= $this->Form->file("file", ["id" => "profile-image-upload","onchange" => "form.submit();","name" =>"file" ,"style" => "display:none;"]) ?>
            <?= $this->Form->hidden("id",["value" => $_GET["id"]]) ?>
            <?= $this->Form->end() ?>
        <?php else: ?>
            <script>
                //$(".black-overlay").remove();
                $(".overlayed-image").removeClass("overlayed-image");
                $(".middle-overlay img").remove();
                $(".image-overlay").css("cursor","default");
            </script>
        <?php endif;?>

        <div class="col-12 mt-3">
            <table class="table table-striped table-bordered profile-table">
                <tbody>
                <tr class="first-name">
                    <th scope="row">First Name</th>
                    <td><?= $this->Miscellaneous->processContent($user->FirstName) ?></td>
                </tr>
                <tr class="last-name">
                    <th scope="row">Last Name</th>
                    <td><?= $this->Miscellaneous->processContent($user->LastName) ?></td>
                </tr>
                <tr class="email">
                    <th scope="row">Email</th>
                    <td><?= $this->Miscellaneous->getText($user->Email) ?></td>
                </tr>
                <tr class="department">
                    <th scope="row">Department</th>
                    <td> <span class="department-content" ><?= $this->Miscellaneous->processContent($user->department) ?></span>
                        <select class="custom-select department-selection" style="display:none;">
                            <option selected="">Unknown</option>
                            <option value="Operations">Operations</option>
                            <option value="Product Stewardship">Product Stewardship</option>
                            <option value="Yordas Hive">Yordas Hive</option>
                            <option value="Hazard Communications">Hazard Communications</option>
                            <option value="Regulatory and Risk Assessment">Regulatory and Risk Assessment</option>
                            <option value="SAS">SAS</option>
                            <option value="Systems and Marketing">Systems and Marketing</option>
                        </select>
                    </td>
                </tr>
                <tr class="role">
                    <th scope="row">Role</th>
                    <td><?= $this->Miscellaneous->processContent($user->role) ?></td>
                </tr>
                <tr class="gender">
                    <th scope="row">Gender</th>
                    <td><?= $this->Miscellaneous->processContent(ucfirst($user->gender)) ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <form action="<?= Router::url(["controller" => "Posts", "action" => "userPosts", "id" => $_GET["id"]])?>" method="get">
                <button type="submit" class="btn btn-block btn-outline-secondary">View All User Posts</button>
                <input type="hidden" value="<?= $user->id ?>" name="id" >
            </form>
        </div>
    </div>

    <div class="col-8">
        <div class="card border-info my-1 status">
            <div class="card-header py-1">
                <b class="h2">Status</b>
            </div>
            <div class="card-body p-2 px-3">
                <p class="card-text"><?= $this->Miscellaneous->processContent($user->status) ?></p>
            </div>
        </div>
        <div class="card bg-light mt-3 about-me">
            <div class="card-header text-center">
                <b class="h2">About Me</b>
            </div>
            <div class="card-body p-2 px-3">
                <p class="card-text"><?= $this->Miscellaneous->processContent($user->about_me) ?></p>
            </div>
        </div>
    </div>

</div>
