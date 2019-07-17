<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */

use Cake\Routing\Router;

$this->set("loadMoreFunction", "loadMorePosts");
$this->extend("home-base");
?>



<?= $this->element("post-image-overlay") ?>
<?= $this->element("post-controls") ?>

<?php
//Generate highlighted post/comment
if (isset($link_post)) {
    if (isset($link_comment_id)) {
        echo $this->element("post" , ["post" => $link_post, "link_comment_id" => $link_comment_id]);
    } else {
        echo $this->element("post" , ["post" => $link_post, "highlight" => true]);
    }
}

?>

<?php foreach ($outputPost as $out): ?>
    <?= $this->element("post", ["post" => $out, "limit" => 2]) ?>
<?php endforeach; ?>

