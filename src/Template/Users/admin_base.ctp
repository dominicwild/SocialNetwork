<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;

?>

<script>

    $(".nav-admin").addClass("active");
    $(".container").removeClass("container").addClass("container-fluid pr-4");

</script>

<div class="row">

    <?= $this->element("admin-side-bar"); ?>

    <div class="col-10 p-0 admin-content pr-3">
        <?= $this->fetch("content"); ?>
    </div>
</div>