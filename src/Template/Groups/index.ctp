<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group[]|\Cake\Collection\CollectionInterface $groups
 */
use Cake\Routing\Router;
?>

<?= $this->Html->css("cards")?>
<?= $this->element("join-btn-js",["csrfToken" => $_COOKIE["csrfToken"]])?>
<?= $this->element("join-btn-large-js",["csrfToken" => $_COOKIE["csrfToken"]])?>

<script>
    $(".nav-groups").addClass("active");
</script>

<div class="row border-bottom border-gray mt-3">
    <div class="col-9">
        <h3>Groups</h3>
    </div>
    <div class="col-3 my-auto">
        <form action="<?= Router::url(["controller"=>"Groups", "action" => "add"])?>">
            <div class="btn-group btn-block justify-content-center">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Create Group</button>
            </div>
        </form>
    </div>
</div>
<div class="row mt-3">
    <?php foreach($groups as $group): ?>
        <div class="col-md-3 d-flex">
            <?= $this->element("group",["group" => $group]);?>
        </div>
    <?php endforeach; ?>
    <?php if(count($groups) == 0): ?>
        <div class="col-12 text-center">
            <h2 class="text-muted"><i>There are no groups. Why not create one?</i></h2>
        </div>
    <?php endif; ?>
</div>