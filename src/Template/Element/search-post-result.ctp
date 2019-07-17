<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>

<div class="row mt-3">
    <div class="col-12">
        <div class="search-hover">
            <div class="search-hover-text">
                a
            </div>
        </div>
        <div class="card search-result">
            <div class="card-body search-main p-0">
                <div class="search-summary search-post-summary">
                    <div class="search-image search-post-image">
                        <img src="<?= $this->Miscellaneous->getImage($post->user->profile_image)?>">
                    </div>
                </div>
                <div class="search-info">
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-post-person-icon" src="/img/si-glyph-person-2.svg">
                        Poster: <span class="ml-1 emphasized-info">
                            <?= $post->user->FirstName . " " . $post->user->LastName ?>
                        </span>
                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-post-comment-icon" src="/img/si-glyph-bubble-message-dot-2.svg">
                        Comments:
                        <span class="ml-1 emphasized-info">
                            <?= sizeof($post->comments) ?>
                        </span>
                    </div>
                    <div class="search-info-row p-1 pl-2">
                        <img class="search-icon mr-1 search-post-time-icon" src="/img/si-glyph-paper-plane.svg">
                        Posted:
                        <span class="ml-1 emphasized-info">
                            <?= $this->Miscellaneous->formatTimeWords($post->created_time) ?>
                        </span>
                    </div>
                </div>
                <div class="search-content py-1 px-2">
                    <h5 class="mb-1"><u>Post Content</u> <a href="<?= Router::url(["controller" => "Posts", "action" => "home", "id" => $post->id])?>" target="_blank">[Go to]</a></h5>
                    <p class="search-text"><?= $this->Miscellaneous->processContent($post->content)?></p>
                </div>
                <div class="search-type post-type p-1">
                    <img class="search-type-icon post-type-icon" src="/img/si-glyph-bubble-chat.svg">
                    <h4 class="mb-1">Post</h4>
                </div>
            </div>
        </div>
    </div>
</div>