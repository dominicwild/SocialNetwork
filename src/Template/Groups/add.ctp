<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 */
use Cake\Routing\Router;

$name = "";
$description = "";

if($this->getRequest()->is("post")){
    $name = $_POST["name"];
    $description = $_POST["description"];
}

?>

<script>
    $(function(){
        bsCustomFileInput.init()
    })
</script>

<div class="row border-bottom border-gray mt-3">
    <div class="col-9">
        <h3>Add a Group</h3>
    </div>
</div>
<?= $this->Form->create(false,["class" => "justify-content-center text-left mt-3", "controller" => "Groups", "action" => "add","enctype" => "multipart/form-data"])?>
    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Group Name:</label>
        <input type="text" value="<?=$name?>" name="name" class="form-control col-10" required>
        <div class="invalid-feedback">
            Please enter a group name.
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Description:</label>
        <textarea value="<?=$description?>" name="description" class="form-control col-10" required></textarea>
        <div class="invalid-feedback">
            Please enter a group name.
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Group Image:</label>
        <div class="custom-file col-10">
            <input type="file" name="image" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
    </div>
    <button class="btn btn-primary" type="submit">Create Group</button>
<?= $this->Form->end()?>


