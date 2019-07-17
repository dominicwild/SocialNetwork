<?php

if(isset($reverse)){
    if($reverse){
        $renderActivity = $renderActivity->toArray();
        $renderActivity = array_reverse($renderActivity);
    }
}

foreach($renderActivity as $activity){
    $this->set("activity", $activity);
    echo $this->element("activity-item");
}


?>