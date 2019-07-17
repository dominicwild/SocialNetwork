<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Comment $comment
 * @var \App\Model\Entity\User $user
 */
use Cake\Routing\Router;
?>

<?php
//$i = 0;
if (isset($limit)) {
    if (strcmp(getType($comments), "Query") == 0) {
        $comments = $comments->toList();
    }
    $comments = array_splice($comments, $limit * -1); //Get last $limit amount of comments
}

?>
<?php foreach ($comments as $comment): ?>
    <?php

        $highlight = "";
        if(isset($link_comment_id) && $comment->id == $link_comment_id){
            $highlight = "highlight";
        }

        ?>
    <div class="slidingDiv">
        <li class="list-group-item px-2 py-1 comment <?= $highlight ?>" data-comment-id= <?= $comment->id ?>>
        <div class="media text-muted ">
            <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $comment->user->id])?>">
            <img class="bd-placeholder-img mr-2 rounded-circle" width="32px" height="64px"
                 src="<?= $this->Miscellaneous->getImage($comment->user->profile_image) ?>" focusable="false" role="img"
                 aria-label="Placeholder: 32x32" style="height: 40px; width: 40px;">
            </a>
            <p class="media-body mb-0 small">
                <span class="row m-0 p-0">
                    <strong class="col-10 px-0 m-0 comment-header"><?= $comment->user->Email ?> <small class="text-muted">&#183; <?= $this->Miscellaneous->formatTime($comment->created_time) ?></small></strong>
                    <span class="btn-group btn-group-sm col-2 m-0 pt-1 p-0 animated" role="group">
                        <?php if ($this->request->getSession()->read('Auth.User.id') === $comment->user->id || $user->Permissions == 100): ?>
                            <button type="button" class="btn btn-group-sm  btn-primary animated comment-btn editBtn" data-pressed=0>
                                <img src="/img/si-glyph-document-edit.svg">
                            </button>
                            <button type="button" class="btn btn-group-sm  btn-danger comment-btn deleteBtn">
                                <img src="/img/si-glyph-trash.svg">
                            </button>
                        <?php endif; ?>
                    </span>
                </span>

                <span class="commentContent"><?= $this->Miscellaneous->processContent($comment->content) ?></span>
            </p>
        </div>
        </li>
    </div>
<?php endforeach; ?>