<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 */
?>

<?php

foreach($posts as $post){
    echo $this->element("post",["post" => $post]);
}

?>