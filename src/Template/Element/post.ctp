<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>
<?php if($post != null): ?>
    <?php
    if (!isset($limit)) {
        $limit = 2;
    }
    $emailNotifyTip = "Email Notifications on Post";
    $highlight_class = "";
    if (isset($highlight) && $highlight) {
        $highlight_class = "highlight";
    }

    //Get correct limit to display comment in the post
    if (isset($link_comment_id)) {
        $count = count($post->comments);
        $i = 0;
        foreach ($post->comments as $comment) {
            if ($comment->id == $link_comment_id) {
                $limit = $count-($i);
                break;
            }
            $i++;
        }
        if($limit < 2){
            $limit = 2;
        } else {
            $limit++;
        }
    }

    $group_post_class = "";

    if(isset($is_group_post) && $is_group_post){
        $group_post_class = "group-post";
    }

    ?>

    <div class="slidingDiv">
    <div class="card mt-3 shadow post <?= $highlight_class  . $group_post_class?>" data-post-id="<?= $post->id ?>">
        <div class="card-body p-2 post-card-body">
            <div class="media small post-card-body-content">
                <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $post->user_id])?>" title="<?= $post->user->FirstName . " " . $post->user->LastName?>">
                    <img class="bd-placeholder-img mr-2 rounded-circle" width="32px" height="64px" src="<?= $this->Miscellaneous->getImage($post->user->profile_image) ?>" focusable="false" role="img" style="height: 64px; width: 64px;">
                </a>
                <p class="media-body mb-0" style = "font-size: 12pt;">
                    <span class="row m-0 p-0">
                        <strong class="col-9 p-0 m-0 poster-name"><?= $post->user->Email ?> <small class="text-muted">&#183; <?= $this->Miscellaneous->formatTime($post->created_time) ?></small></strong>
                        <span class="col-3 p-0 d-flex flex-row-reverse">
                            <?php if($post->hasNotification):?>
                                <button type="button" class="btn btn-group-sm btn-success ml-2 notification-btn" data-toggle="tooltip" data-placement="top" title="<?= $emailNotifyTip ?>">
                                    <img src="/img/si-glyph-mail-send.svg" class="post-btn-icon">
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-group-sm btn-secondary ml-2 notification-btn" data-toggle="tooltip" data-placement="top" title="<?= $emailNotifyTip ?>">
                                    <img src="/img/si-glyph-mail.svg" class="post-btn-icon">
                                </button>
                            <?php endif; ?>

                            <?php if($post->user->id == $user["id"] || $user["Permissions"] === 100):?>
                            <span class="btn-group btn-group-sm m-0 p-0 animated post-btn-group" role="group">
                                    <button type="button" class="btn btn-group-sm  btn-primary editBtn" data-pressed="0" title="Edit Post">
                                        <img src="/img/si-glyph-document-edit.svg" class="post-btn-icon">
                                    </button>
                                    <button type="button" class="btn btn-group-sm btn-danger deleteBtn" title="Delete Post">
                                        <img src="/img/si-glyph-trash.svg" class="post-btn-icon">
                                    </button>
                            </span>
                            <?php else: ?>
                                <?php if($post->hasReported): ?>
                                    <span class="flag">
                                        <img class="p-1" draggable="false" src="/img/flag-click.svg" title="Flagged Post">
                                    </span>
                                <?php else: ?>
                                    <span class="report-btn flag">
                                        <img class="p-1" draggable="false" src="/img/flag.svg" title="Flag Post">
                                    </span>
                                <?php endif;?>

                            <?php endif; ?>
                    </span>
                    </span>
                    <span class="commentContent"><?= $this->Miscellaneous->processContent($post->content) ?></span>
                </p>
            </div>
        </div>
        <div class="read-more" style="display:none;">
            <button class="btn btn-block text-white p-0 m-0 read-more-btn">
                <img class="read-more-arrow" src="/img/Arrow-down.svg"> Read More <img class="read-more-arrow" src="/img/Arrow-down.svg">
            </button>
        </div>

        <?php $this->set("post", $post);
            echo $this->element("poll");
        ?>


        <?php if($post->post_images != []): ?>
        <div class="post-image-container">
            <?php $i = 0; foreach($post->post_images as $image): ?>
                <?php $i++; ?>
                <?php if($i > 2): ?>
                    <div class="post-image">
                        <?php if(count($post->post_images) > 9): ?>
                            <div class="image-more-message">
                                <span class="two-digit">+<?=count($post->post_images)?></span>
                            </div>
                        <?php else: ?>
                            <div class="image-more-message">
                                <span>+<?=count($post->post_images)?></span>
                            </div>
                        <?php endif; ?>
                        <img src="<?= $image->image ?>">
                    </div>
                <?php break; ?>
                <?php else: ?>
                    <div class="post-image">
                            <img src="<?= $image->image ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif ?>
        <ul class="list-group list-group-flush ml-0">

                <li class="list-group-item p-0" id="loadMore"  <?php if ($limit >= count($post->comments)): ?>style = "display:none;" <?php endif; ?>>
                    <button class="btn btn-block text-white bg-info p-0 m-0 loadMoreBtn" data-pressed=0 style="border-radius: 0; transition:background-color 0.5s ease;z-index: 3;">
                        Load More Comments
                    </button>
                </li>
            <?php if(isset($link_comment_id)){
                echo $this->element("comments", ["comments" => $post->comments, "limit" => $limit, "link_comment_id" => $link_comment_id]);
            } else {
                echo $this->element("comments", ["comments" => $post->comments, "limit" => $limit]);
            }
            ?>
        </ul>
        <div class="card-body p-2 border-top">
            <form>
                <div class="form-group m-0 p-1 pr-2">
                    <textarea class="form-control contentArea commentArea" placeholder="Comment" id="comment" rows="1"></textarea>
                </div>
            </form>
        </div>
    </div>
    </div>
<?php else: ?>
    <div class="row mt-1">
        <div class="col-12 text-center">
            <h3 class="text-muted"><i>This post does not exist</i></h3>
        </div>
    </div>
<?php endif;?>
