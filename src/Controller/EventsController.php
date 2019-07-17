<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Activity;
use App\Model\Entity\Post;
use App\Model\Table\CommentsTable;
use Aura\Intl\Exception;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Queue\Model\Table\QueuedJobsTable;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\GroupsTable $Groups
 * @property \App\Model\Table\GroupMembersTable $GroupMembers
 * @property \App\Model\Table\PostsTable $Posts
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property QueuedJobsTable $QueuedJobs
 * @property CommentsTable $Comments
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Posts', 'Groups'],
            "order" => ["date" => "ASC"],
            "where" => ["date >=" => time()]
        ];

//        $events = $this->paginate($this->Events);
        $user_id = $this->Auth->user("id");
        $events = $this->Events->find()
            ->where(["date >=" => time()])
            ->order(["date" => "ASC"])
            ->find("EventCard",["user" => $user_id]);

        $this->loadModel("Groups");
        $this->loadModel("GroupMembers");
        $this->loadModel("EventParticipants");

        if(isset($_GET["comment_id"])){
            $link_event = $this->getEventComment($_GET["comment_id"]);
        } elseif(isset($_GET["id"])){
            $link_event = $this->getEvent($_GET["id"]);
        }

        $this->set("calendar_id",Configure::read("GoogleSharedCalender.calendar_id"));
        $this->set(compact('events'));
    }

    private function getEvent($id){
        try {
            $user_id = $this->Auth->user("id");
            $event = $this->Events->find("RenderContent",["user" => $user_id])->where(["Events.id" => $id])->toArray();

            if($event != []){
                $this->set("link_event", $event[0]);
                return $event;
            } else {
                $this->Flash->error("This event either does not exist or has been deleted");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("This event either does not exist or has been deleted");
        }
    }

    private function getEventComment($id){
        try {
            $user_id = $this->Auth->user("id");
            $this->loadModel("Comments");
            $comment = $this->Comments->get($id, ["contain" => ["Posts" => ["Events"]]]);
            $event_id = $comment->post->event->id;
            $event = $this->Events->find("RenderContent",["user" => $user_id])->where(["Events.id" => $event_id]);

            if ($event) {
                $this->set("link_event", $event->toArray()[0]);
                $this->set("link_comment_id", $comment->id);
                return $event;
            } else {
                $this->Flash->error("This event either does not exist or has been deleted");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("This event either does not exist or has been deleted");
        }
    }

    public function loadMore(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $id = $_POST["id"];
            $event = $this->Events->get($id,["contain" => "Posts"]);
            $latest_time = $event->post->created_time;
            $events = $this->viewAllQuery()->where(["Posts.created_time <=" => $latest_time, "Events.id !=" => $event->id ]);
            $this->set("events", $events);
            return;
        }
        return $this->response->withStringBody("false");
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $event = $this->Events->get($id, [
            'contain' => ['Posts', 'Groups', 'EventParticipants']
        ]);

        $this->set('event', $event);
    }

    private function randomEventImage(){
        $path = WWW_ROOT . "img/default/event/";
        $files = array_diff(scandir($path), array('.', '..'));
        $rand = rand(2,sizeof($files));
        debug($files);
        debug($rand);
        return "/img/default/event/" . $files[$rand];
    }

    public function validateDate($event){
        if(isset($_POST["end_date"]) && $_POST["end_date"] != "") {
            $event->end_date = strtotime($_POST["end_date"]);
            if($event->end_date < $event->date){
                $this->Flash->error("End date is before the start date.");
                $group_options = $this->getGroupOptions();
                $this->set("group_options",$group_options);
                return true;
            }
        }
        return false;
    }

    public function add() {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $this->loadModel("Posts");
            $this->loadModel("EventParticipants");
            $event = $this->Events->patchEntity($event, $this->request->getData());
            $event->date = strtotime($_POST["date"]);

            if($this->validateDate($event)) {
                return;
            }

            $this->uploadEventImage($event);
            $event->post_id = $this->addPost(false,Post::TYPE_EVENT);

            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been created.'));
                $event_participant = $this->EventParticipants->newEntity();
                $event_participant->user_id = $this->Auth->user("id");
                $event_participant->event_id = $event->id;
                if($this->EventParticipants->save($event_participant)){
                    $this->loadModel('Queue.QueuedJobs');
                    $this->QueuedJobs->createJob('EventNotification', ["id" => $event->id]);//First param task name, second data to send to task, third options {notBefore, priority, group}
                    $data = [
                        "user_id" => $this->Auth->user("id"),
                        "id" => $event->id,
                        "action" => Activity::EVENT_ADD,
                        "time" => microtime(true),
                    ];
                    $this->QueuedJobs->createJob('LogActivity',$data);

                    $data = [
                        "email" => $this->Auth->user("Email"),
                        "event_id" => $event->id,
                    ];
                    $this->QueuedJobs->createJob('AddGoogleCalendarEvent',$data,["priority" => 6]);

                }
                return $this->redirect(["action" => "index"]);
            }
            $this->Flash->error(__('The event could not be saved. Please, try again.'));
        }
        $group_options = $this->getGroupOptions();
        $this->set("group_options",$group_options);
    }

    public function uploadEventImage($event){
        if($_FILES["image"]["error"] == 0 && explode("/",$_FILES["image"]["type"])[0] === "image") {
            $event->image = $this->uploadImage($_FILES["image"]);
        } else {
            $event->image = $this->randomEventImage();
            $this->Flash->error(__('Image upload failed or file submitted was not an image.'));
        }
    }

    public function edit($id = null) {
        $this->viewBuilder()->setTemplate("add");
        $this->set("edit", true);
        $format = "m/d/Y g:i A";

        if($this->request->is("get")) {
            if (isset($_GET["id"])) {
                $event = $this->Events->get($_GET["id"], ["contain" => ["Posts" => "Users"]]);
            } else {
                return $this->response->withStatus(404);
            }
        }

        if($this->request->is("post")){
            $event = $this->Events->get($_POST["id"], ["contain" => ["Posts" => "Users"]]);

            if($event->post->user->id === $this->Auth->user("id")){
                $event->title = $_POST["title"];
                $event->post->content = $_POST["content"];
                $event->group_id = $_POST["group_id"];
                $event->place = $_POST["place"];
                $event->date = strtotime($_POST["date"]);
                if($_POST["end_date"] !== ""){
                    $event->end_date = strtotime($_POST["end_date"]);
                }

                if($this->validateDate($event)) {
                    $this->set("id", $_POST["id"]);
                    return;
                }

                if(isset($_FILES["image"])){
                    $this->uploadEventImage($event);
                }
                $this->loadModel("Posts");
                if($this->Events->save($event) && $this->Posts->save($event->post)){
                    $this->Flash->success("Changes applied to event.");

                    $this->loadModel('Queue.QueuedJobs');
                    $data = [
                        "user_id" => $this->Auth->user("id"),
                        "id" => $event->id,
                        "action" => Activity::EVENT_EDIT,
                        "time" => microtime(true),
                    ];
                    $this->QueuedJobs->createJob('LogActivity',$data);

                    return $this->redirect(Router::url(["controller" => "Events", "action" => "index"],true));
                }
            } else {
                return $this->response->withStatus(403);
            }
        } else {
            if (isset($_GET["id"])) {
                $_POST["title"] = $event->title;
                $_POST["content"] = $event->post->content;
                $_POST["group_id"] = $event->group_id;
                $_POST["place"] = $event->place;
                $_POST["date"] = date($format, $event->date);
                $_POST["end_date"] = $event->end_date === null ? "" : date($format, $event->end_date);

                $group_options = $this->getGroupOptions();
                $this->set("group_options", $group_options);
            }
        }
    }

//    public function edit2($id = null) {
//        if($this->request->is("post")){
//            $id = $_POST["id"];
//        } else {
//            $id = $_GET["id"];
//        }
//
//        $event = $this->Events->get($id, ['contain' => ["Posts"]]);
//        if($this->Auth->user("id") == $event->post->user_id) {
//            if ($this->request->is("post")) {
//                $event = $this->Events->patchEntity($event, $this->request->getData());
//                $event->date = strtotime($_POST["date"]);
//                $this->uploadEventImage($event);
//                $post = $event->post;
//                $post->content = $_POST["content"];
//                $event->post = $post;
//                if ($this->Events->save($event)) {
//                    $this->Flash->success(__('The event has been saved.'));
//                    $this->loadModel('Queue.QueuedJobs');
//                    $data = [
//                        "user_id" => $this->Auth->user("id"),
//                        "id" => $event->id,
//                        "action" => Activity::EVENT_EDIT,
//                        "time" => microtime(true),
//                    ];
//                    $this->QueuedJobs->createJob('LogActivity',$data);
//
//                    $data = [
//                        "email" => $this->Auth->user("Email"),
//                        "event_id" => $event->id,
//                        "edit" => true,
//                    ];
//                    $this->QueuedJobs->createJob('AddGoogleCalendarEvent',$data,["priority" => 6]);
//
//                    return $this->redirect(['action' => 'index']);
//                } else {
//                    $this->Flash->error(__('The event could not be saved. Please, try again.'));
//                }
//            }
//            $group_options = $this->getGroupOptions();
//            $this->set("group_options", $group_options);
//            $this->set("event", $event);
//        } else {
//            $this->Flash->error("This event does not exist or you don't have permissions to edit it.");
//            $this->redirect($this->referer());
//        }
//    }

    private function getGroupOptions(){
        $this->loadModel("Groups");
        $groups = $this->Groups->find()->select(["id","name"])->toArray();
        $group_options = [];
        foreach($groups as $group){
            $group_options[$group->id] = $group->name;
        }
        return $group_options;
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        $calendar_id = $event->calendar_event_id;
        if ($this->Events->delete($event)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function fetchEventPost(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $this->loadModel("EventParticipants");
            $id = $_POST["event_id"];
            $user_id = $this->Auth->user("id");
            $event = $this->Events->find("RenderContent" , ["user" => $user_id])->where(["Events.id" => $id])->toArray()[0];
            $this->set("event",$event);
            return $this->render("/Element/event-post");
        }
    }

    public function viewAll(){
        $events = $this->viewAllQuery();
        $this->set("events", $events);
    }

    private function viewAllQuery(){
        $user_id = $this->Auth->user("id");
        return $this->Events->find("RenderContent", ["user" => $user_id])->limit(10);
    }
}
