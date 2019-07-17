<?php



?>

<ol class="carousel-indicators">
    <li data-target="#carouselPost" data-slide-to="0" class="active"></li>
    <?php for($i = 1;$i < count($post_images); $i++):?>
    <li data-target="#carouselPost" data-slide-to="<?=$i?>"></li>
    <?php endfor; ?>
</ol>
<div class="carousel-inner">
    <div class="carousel-item active carousel-image">
        <img src="<?=$post_images[0]->image?>" class="d-block h-100">
    </div>
    <?php unset($post_images[0]) ?>
    <?php foreach($post_images as $image):?>
    <div class="carousel-item carousel-image">
        <img src="<?=$image->image?>" class="d-block h-100">
    </div>
    <?php endforeach; ?>
</div>
<a class="carousel-control-prev" href="#carouselPost" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#carouselPost" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
</a>

