<?php

/**
 * @var \Cake\View\View $this
 */

$this->set("limit",3);

$numEvents =  count($events);
$numPosts = count($group_posts);
$i = 0;
$j = 0;

while ($i < $numEvents || $j < $numPosts) {
    if ($i < $numEvents && $j < $numPosts) {
        $event = $events[$i];
        $post = $group_posts[$j];
        if ($event->post->created_time > $post->post->created_time) {
            renderEvent($this,$event);
            $i++;
        } else {
            renderPost($this,$post);
            $j++;
        }
    } else {
        if ($i < $numEvents) {
            $event = $events[$i];
            renderEvent($this,$event);
            $i++;
        } else {
            $post = $group_posts[$j];
            renderPost($this,$post);
            $j++;
        }
    }
}

function renderEvent(\Cake\View\View $view, $event){
    echo "<div class='border-bottom border-gray pb-1 w-100 event-post'>";
    $view->set("event", $event);
    echo $view->element("event-post");
    echo "</div>";
}

function renderPost(\Cake\View\View $view,$post){
    echo "<div class='col-12 p-0 border-bottom border-gray pb-4'>";
    $view->set("post",$post->post);
    echo $view->element("post",["is_group_post" => true]);
    echo "</div>";
}

?>