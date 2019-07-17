<?php


foreach($events as $event){
    $this->set("event", $event);
    echo $this->element("event-post");
}

?>