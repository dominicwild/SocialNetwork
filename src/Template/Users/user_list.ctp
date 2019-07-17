<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\User $users
 */
use Cake\Routing\Router;



$this->extend("/Posts/home_base");
?>

<script>
    $(function () {
        $("#userTable").DataTable({
            "columns": [
                {"orderable": false},
                {"orderable": true},
                {"orderable": true},
                {"orderable": true},
                {"orderable": false},
            ],
            "lengthChange": false
        });
    });
</script>

<?php echo $this->Html->css("user_list.css")?>
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
            <th class="text-center align-middle department-col">
                Department
            </th>
            <th class="text-center align-middle buttons-col">
                Actions
            </th>
        </thead>
        <tbody>
            <?php foreach($users as $user_data):?>
            <tr>
                <td class="text-center align-middle icon-col">
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
                <td class="text-center align-middle department-col">
                    <?= $user_data->department ?>
                </td>
                <td class="text-center align-middle buttons-col">
                    <form class="user-list-btn" action="<?= Router::url(["controller" => "Posts", "action" => "userPosts"])?>" method="get">
                        <button type="submit" class="btn btn-block btn-sm btn-info">Users Posts</button>
                        <input type="hidden" value="<?= $user_data->id ?>" name="id" >
                    </form>
                    <form class="user-list-last-btn" action="<?= Router::url(["controller" => "Users", "action" => "profile"])?>" method="get">
                        <button type="submit" class="btn btn-block btn-sm btn-info">Profile</button>
                        <input type="hidden" value="<?= $user_data->id ?>" name="id" >
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>