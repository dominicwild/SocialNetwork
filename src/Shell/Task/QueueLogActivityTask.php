<?php
namespace App\Shell\Task;

use App\Model\Entity\Activity;
use App\Model\Entity\Post;
use App\Model\Table\ActivityTable;
use App\Model\Table\CommentsTable;
use App\Model\Table\GroupPostsTable;
use App\Model\Table\GroupsTable;
use App\Model\Table\UsersTable;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Queue\Shell\Task\QueueTask;

//use Tools\Mailer\Email;
/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PostsTable $Posts
 * @property GroupPostsTable $GroupPosts
 * @property GroupsTable $Groups
 * @property ActivityTable $Activity
 * @property CommentsTable $Comments
 * @var Post $post
 */
class QueueLogActivityTask extends QueueTask {

    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return bool Success
     */
    public function run(array $data, $jobId) {

        $action = $data["action"];
        $id = $data["id"];
        $user_id = $data["user_id"];
        $params = [];

        $this->loadModel("Activity");
        $this->loadModel("Users");
        $this->loadModel('Queue.QueuedJobs');

        $job = $this->QueuedJobs->get($jobId);

        switch($action){
            case Activity::POST_ADD:
                $params = $this->postAdd($id );
                break;
            case Activity::POST_EDIT:
                $params = $this->postEdit($id);
                break;
            case Activity::COMMENT_ADD:
                $params = $this->commentAdd($id);
                break;
            case Activity::COMMENT_EDIT:
//                $params = $this->commentEdit($id);
                break;
            case Activity::EVENT_EDIT:
                $params = $this->eventEdit($id);
                break;
            case Activity::GROUP_ADD:
                $params = $this->groupAdd($id);
                break;
            case Activity::GROUP_EDIT:
                $params = $this->groupEdit($id);
                break;
            case Activity::PROFILE_EDIT:
                $params = $this->profileEdit($id);
                break;
        }

        if($params == []){
            return true;
        }

        $activity = $this->Activity->newEntity();
        $activity->link = substr(APPLICATION_DIR,0,strlen(APPLICATION_DIR)-1) . $params["link"];
        $activity->user_id = $user_id;
        $activity->description = $params["log"];
        $activity->time = $data["time"];

        return $this->Activity->save($activity);
    }

    public function postAdd($id){
        $this->loadModel("Events");
        $event = $this->Events->find()->where(["post_id" => $id])->first();
        if($event){
            $log = "Posted a new event named <b>" . h($event->title) . "</b>";
            $link = Router::url(["controller" => "Events", "action" => "index", "id" => $event->id], true);
            return ["log" => $log, "link" => $link];
        }

        $this->loadModel("GroupPosts");
        $group_post = $this->GroupPosts->find()->contain(["Groups"])->where(["post_id" => $id])->first();
        if($group_post){
            $log = "Submitted a new post in group <b>" . h($group_post->group->name) . "</b>";
            $link = Router::url(["controller" => "Groups", "action" => "view", "id" => $group_post->group_id, "post_id" => $id], true);
            return ["log" => $log, "link" => $link];
        }

        $log = "Submitted a <b>new post</b>";
        $link = Router::url(["controller" => "Posts", "action" => "home", "id" => $id], true);
		
		debug($link);
        return ["log" => $log, "link" => $link];
    }

    public function postEdit($id){
        $log = "<b>Edited</b> a post";
        $link = Router::url(["controller" => "Posts", "action" => "home", "id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function commentAdd($id){
        $this->loadModel("Events");
        $this->loadModel("Comments");
        $comment = $this->Comments->get($id);
        $event = $this->Events->find()->where(["post_id" => $comment->post_id])->first();
        if($event){
            $log = "Commented on an event named <b>" . h($event->title) . "</b>";
            $link = Router::url(["controller" => "Events", "action" => "index", "id" => $event->id, "comment_id" => $comment->id], true);
            return ["log" => $log, "link" => $link];
        }

        $this->loadModel("GroupPosts");
        $group_post = $this->GroupPosts->find()->contain(["Groups"])->where(["post_id" => $comment->post_id])->first();
        if($group_post){
            $log = "Commented on a post in group <b>" . h($group_post->group->name) . "</b>";
            $link = Router::url(["controller" => "Groups", "action" => "view", "id" => $group_post->group_id, "post_id" => $comment->post_id, "comment_id" => $comment->id], true);
            return ["log" => $log, "link" => $link];
        }

        $log = "<b>Commented</b> on a post";
        $link = Router::url(["controller" => "Posts", "action" => "home", "comment_id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function commentEdit($id){
        $log = "Edited a comment on a post";
        $link = Router::url(["controller" => "Posts", "action" => "home", "comment_id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function groupAdd($id){
        $this->loadModel("Groups");
        $group = $this->Groups->get($id);
        $log = "Created a new group named <b>" . h($group->name) . "</b>";
        $link = Router::url(["controller" => "Groups", "action" => "view", "id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function groupEdit($id){
        $this->loadModel("Groups");
        $group = $this->Groups->get($id);
        $log = "Edited the group named <b>" . h($group->name) . "</b>";
        $link = Router::url(["controller" => "Groups", "action" => "view", "id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function eventEdit($id){
        $this->loadModel("Events");
        $event = $this->Events->get($id);
        $log = "Edited the event <b>" . h($event->title) . "</b>";
        $link = Router::url(["controller" => "Events", "action" => "index", "id" => $id], true);
        return ["log" => $log, "link" => $link];
    }

    public function profileEdit($user_id){
        $this->loadModel("Users");
        $log = "Edited their <b>profile</b>.";
        $link = Router::url(["controller" => "Users", "action" => "profile", "id" => $user_id], true);
        return ["log" => $log, "link" => $link];
    }

}
