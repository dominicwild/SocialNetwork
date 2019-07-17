<?php
/**
 * @var \App\Model\Entity\User $target_user
 * @var \Cake\View\View $this
 */
?>


<script>
    function loadMoreData(data){
        data.append("id", <?= $_GET["id"] ?>);
    }
</script>

<?php
if($target_user == $user){
    $title = "Your Posts";
} else {
    $title = $target_user->FirstName . " " . $target_user->LastName . "'s Posts";
}

$this->set("loadMoreFunction", "loadMoreMyPosts");
$this->extend("home-base");
?>


<?= $this->element("post-image-overlay")?>
<?php //$this->element("post-controls")?>

<div class="row border-bottom border-gray mt-3 mx-1">
    <div class="col-9">
        <h3 class="mb-1"><?= $title ?></h3>
    </div>
</div>

<?php foreach($outputPost as $out): ?>
    <?= $this->element("post", ["post" => $out, "limit" => 2])?>
<?php endforeach; ?>

<?php if(count($outputPost->toArray()) == 0): ?>
<div class="col-12 text-center">
    <h2 class="text-muted"><i>It appears this user has no posts.</i></h2>
</div>
<?php endif; ?>
