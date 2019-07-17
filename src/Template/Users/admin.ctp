<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\User $users
 */
use Cake\Routing\Router;

$deleteConfirmText = "You are about to delete a user and all their associated content. This means all posts, polls, images, events etc. will be deleted that were made by this user.";

$this->extend("admin_base")
?>

<script>
    $(".nav-admin").addClass("active");
</script>

<?= $this->Html->css("large-bootstrap-controls.css");?>
<?= $this->Html->css("admin.css"); ?>
<?php echo $this->Html->css("user_list.css")?>

<script>

    function deleteUser(e){

        var btn = e.target;
        var id = $(btn).closest(".user").data("id");

        if(confirm("<?= $deleteConfirmText ?> \n\nIs this OK?" )){
            $.ajax({
                url: "<?= Router::url(['controller' => 'Users', 'action' => 'delete']); ?>",
                type: "post",
                data: {id: id},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
                },
                success: function (data) {
                    if (data === "true") {
                        $(btn).closest("tr").slideUp();
                    } else {

                    }
                }
            });
        }
    }

    function ambassadorCheckboxClick(e){

        var id = $(e.target).closest(".user").data("id");
        var checked = e.target.checked;

        $.ajax({
            url: "<?= Router::url(['controller' => 'Ambassadors', 'action' => 'updateAmbassador']); ?>",
            type: "post",
            data: {id: id, ambassador: checked},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
            },
            success: function (data) {

            }
        });
    }

    function adminCheckboxClick(e){
        var id = $(e.target).closest(".user").data("id");
        var checked = e.target.checked;

        $.ajax({
            url: "<?= Router::url(['controller' => 'Users', 'action' => 'updateAdmin']); ?>",
            type: "post",
            data: {id: id, admin: checked},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', "<?= $_COOKIE["csrfToken"] ?>");
            },
            success: function (data) {

            }
        });
    }

    $(function () {
        $("#userTable").DataTable({
            "columns": [
                {"orderable": false},
                {"orderable": true},
                {"orderable": true},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
            ],
            "lengthChange": false
        });

        $(".delete-btn").on("click",deleteUser);
        $(".ambassador-col input[type=checkbox]").on("click",ambassadorCheckboxClick);
        $(".admin-col input[type=checkbox]").on("click",adminCheckboxClick);
    });

</script>

<div class="row border-bottom border-gray mt-3">
    <div class="col-12">
        <h3>Admin</h3>
    </div>
</div>

<div class="col-12 mt-3 p-0">
    <table class="table table-striped user-list-table mt-3" cellspacing="0" id="userTable">
        <thead>
        <th class="text-center align-middle icon-col">
            Icon
        </th>
        <th class="text-center align-middle first-name-col">
            First Name
        </th>
        <th class="text-center align-middle last-name-col">
            Last Name
        </th>
        <th class="text-center align-middle ambassador-col">
            Ambassador
        </th>
        <th class="text-center align-middle ambassador-col">
            Admin
        </th>
        <th class="text-center align-middle buttons-col">
            Actions
        </th>
        </thead>
        <tbody>
        <?php foreach($users as $user_data):?>
        <?php //debug($user_data); ?>
            <tr class="user" data-id="<?=$user_data->id?>">

                <td class="text-center align-middle icon-admin-col py-1">
                    <a href="<?= Router::url(["controller" => "Users", "action" => "profile" , "id" => $user_data->id])?>">
                        <img class="rounded-circle user-list-icon" src="<?= $this->Miscellaneous->getImage($user_data->profile_image) ?>" role="img">
                    </a>
                </td>

                <td class="text-center align-middle first-name-col">
                    <?= $user_data->FirstName ?>
                </td>

                <td class="text-center align-middle last-name-col">
                    <?= $user_data->LastName ?>
                </td>

                <td class="text-center align-middle ambassador-col">
                    <div class="custom-control custom-checkbox options-checkbox form-control-lg">
                        <input type="checkbox" class="custom-control-input" id="ambassador_<?=$user_data->id?>" <?php if($user_data->ambassador != null){ echo "checked";} ?>>
                        <label class="custom-control-label" for="ambassador_<?=$user_data->id?>"></label>
                    </div>
                </td>

                <td class="text-center align-middle admin-col">
                    <div class="custom-control custom-checkbox options-checkbox form-control-lg">
                        <input type="checkbox" class="custom-control-input" id="admin_<?=$user_data->id?>" <?php if($user_data->Permissions == 100){ echo "checked";} ?>>
                        <label class="custom-control-label" for="admin_<?=$user_data->id?>"></label>
                    </div>
                </td>

                <td class="text-center align-middle buttons-col">
                    <button class="btn btn-block btn-sm btn-danger delete-btn">Delete User</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>