<?php

namespace App\View\Helper;

use Cake\View\Helper;
use DateTime;
use Emojione\Client;
use Emojione\Ruleset;

class MiscellaneousHelper extends Helper {


    private $client;

    public function  __construct(){
        $this->client = new Client(new Ruleset());
        $this->client->riskyMatchAscii = true;
        $this->client->greedyMatch = true;
    }

    public function formatTime($time) {
        if($time < 0){
            return "Never";
        }
//        debug($time);
        $time = (int)$time;
        $origin_date = new DateTime();
        $origin_date->setTimeStamp($time);
        $since_start = $origin_date->diff(new DateTime());

        if($since_start->y > 0){
            return date("j M Y",$time) . "  &#183;" . date(" g:iA",$time) . "";
        }
        if($since_start->m > 0){
            return date("j M",$time) . "  &#183;" . date(" g:iA",$time) . "";
        }
        if($since_start->d > 0){
            return $since_start->d . "d";
        }
        if($since_start->h > 0){
            return $since_start->h . "h";
        }
        if($since_start->i > 0){
            return $since_start->i . "m";
        }
        if($since_start->s > 0){
            return $since_start->s . "s";
        }
        return "1s";
    }

    public function voteText($voteNum){
        return  $voteNum != 1 ? $voteNum . " Votes" : "1 Vote";
    }

    public function expireText($time){
        $time = (int)$time;
        $expire_date = new DateTime();
        $expire_date->setTimeStamp($time);
        $current_date = new DateTime();
        $to_expire = $current_date->diff($expire_date);
        $prefix = "Expires in ";
        $units = [];

        if($to_expire->d > 0){
            if($to_expire->d == 1){
                $units[] = "1 day and ";
            } else {
                $units[] = $to_expire->d . " days and ";
            }
        }

        if($to_expire->h > 0){
            if($to_expire->h == 1){
                $units[] = "1 hour and ";
            } else {
                $units[] = $to_expire->h . " hours and ";
            }
        }

        if(count($units) < 2 && $to_expire->i > 0){
            if($to_expire->i == 1){
                $units[] = "1 minute and ";
            } else {
                $units[] = $to_expire->i . " minutes and ";
            }
        }

        if(count($units) < 2 && $to_expire->s > 0){
            if($to_expire->s == 1){
                $units[] = "1 second and ";
            } else {
                $units[] = $to_expire->s . " seconds and ";
            }
        }

        if(count($units) == 2){
            $units[1] = substr($units[1],0,-4);
        } else {
            $units[0] = substr($units[0],0,-4);
            $units[1] = "";
        }

        return $prefix . $units[0] . $units[1];
    }

    public function formatTimeWords($time) {
        if ($time < 0) {
            return "Never";
        }
        $origin_date = new DateTime();
        $origin_date->setTimeStamp($time);
        $since_start = $origin_date->diff(new DateTime());

        if ($since_start->y > 0) {
            return date("jS M Y", $time);// . "  &#183;" . date(" g:iA", $time) . "";
        }
        if ($since_start->m > 0) {
            return date("jS M", $time) ;//. "  &#183;" . date(" g:iA", $time) . "";
        }
        if ($since_start->d == 1) {
            return $since_start->d . " day ago";
        } elseif ($since_start->d > 0) {
            return $since_start->d . " days ago";
        }
        if ($since_start->h == 1) {
            return $since_start->h . " hour ago";
        } elseif ($since_start->h > 0) {
            return $since_start->h . " hours ago";
        }
        if ($since_start->i == 1) {
            return $since_start->i . " minutes ago";
        } elseif ($since_start->i > 0) {
            return $since_start->i . " minutes ago";
        }
        if ($since_start->s == 1) {
            return $since_start->s . " second ago";
        } elseif ($since_start->s > 0) {
            return $since_start->s . " seconds ago";
        }
        return "1 second ago";
    }

    public function toEditTimeFormat($time){
        return date("m/d/Y g:i A", $time);
    }

    public function getImage($path) {
        $relative_path = substr(WWW_ROOT,0,strlen(WWW_ROOT)-1);//"webroot";
        $arrayExt = explode(".", $path);
        $extension = strtolower(end($arrayExt));
		//debug($relative_path . $path);
        if($extension != "" && file_exists($relative_path . $path)){
            return $path;
        } else {
            return "/img/Black_question.svg";
        }
    }

    public function getText($text){
        if($text == null){
            return "Unknown";
        } else {
            return h($text);
        }
    }

    public function eventTime($time, $onlyDate = false){
        if($time != null){
           $current_year = date("Y",time());
           $event_year = date("Y",$time);
            if(strcmp($current_year,$event_year) == 0) { //If same year
                if($onlyDate){
                    return date("M jS", $time);
                }
                return date("M jS g:ia", $time);
            } else {
                if($onlyDate){
                    return date("M jS Y", $time);
                }
                return date("M jS Y g:ia", $time);
            }
        }
        return "Unknown";
    }

    public function processContent($content){
        //return $content;
//        return $this->client->toImage($content);
//        $regex = "/[♀♂]/";
//        $content = preg_replace($regex, '', $content);
//        $regex = "/[♀♂](\W)/";
//        $content = preg_replace($regex, '$1', $content);
        return $this->client->toImage($this->hyperlinkText(h($content)));
    }

    public function hyperlinkText($text){
        //text = text.replace(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/gm,'<a href="$&">$&</a>').replace(/([\w\d\.]+\@[\w\d\.]+(\/)?)/gm,'<a href="mailto:$1$>$1</a>');
//        $url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
//        $regexURL = '/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/';
//        $regexEmail = '/([\w\d\.]+\@[\w\d\.]+(\/)?)/';
//        $regexURL = "@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@";
        $regexURL = "~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i";
        $text = preg_replace($regexURL, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $text);
        //$text = preg_replace($regexEmail, '<a href="mailto:$1">$1</a>', $text);
        return $text;
    }

}


































