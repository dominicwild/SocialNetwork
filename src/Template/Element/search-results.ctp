<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 * @var string $search
 * @var array $search_results
 */

foreach($search_results as $result){
    $class = explode("\\",get_class($result));
    switch(array_pop($class)){
        case "Event":
            echo $this->element("search-event-result", ["event" => $result]);
            break;
        case "Post":
            echo $this->element("search-post-result", ["post" => $result]);
            break;
        case "Group":
            echo $this->element("search-group-result", ["group" => $result]);
            break;
    }
}

?>