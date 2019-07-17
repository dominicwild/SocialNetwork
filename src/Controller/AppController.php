<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Activity;
use App\Model\Entity\Post;
use App\Model\Table\EventParticipantsTable;
use App\Model\Table\PollOptionsTable;
use App\Model\Table\PollsTable;
use App\Model\Table\PostsTable;
use App\Model\Table\UserPostNotificationsTable;
use App\Model\Table\UsersTable;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Google_Client;
use Google_Service_Calendar;
use Queue\Model\Table\QueuedJobsTable;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 * @property \App\Model\Table\GroupsTable $GroupMembers
 * @property \App\Model\Table\PostsTable $Posts
 * @property \App\Model\Table\PostImagesTable $PostImages
 * @property \App\Model\Table\GroupPostsTable $GroupPosts
 * @property UserPostNotificationsTable $UserPostNotifications
 * @property EventParticipantsTable $EventParticipants
 * @property UsersTable $Users
 * @property PollsTable $Polls
 * @property PollOptionsTable $PollOptions
 * @property QueuedJobsTable $QueuedJobs
 * @property ActivityTable $Activity
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

        //$this->loadComponent('RequestHandler', ['enableBeforeRedirect' => false]);
        $this->loadComponent('Flash');
        //$this->loadComponent('CakeDC/Users.UsersAuth');

        $this->loadComponent('Auth', [
            //use isAuthorized in Controllers
            'authorize' => ['Controller'],
            // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer()
        ]);

        $this->loadComponent("Cookie");

        // Allow the display action so our PagesController
        // continues to work. Also enable the read only actions.
        //$this->Auth->allow(['display', 'view', 'index']);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');

        $this->updateUser();
    }

    public function updateUser(){
        $this->loadModel('Users');
        $logged_in = $this->Auth->user() != null;
        $user = $this->Users->find()->where(["id" => $this->Auth->user("id")])->first();
        $this->set("logged_in", $logged_in);
        $this->set("logged_user", $user);
        if($logged_in){
            $this->Auth->setUser($user);
        } else {
            $this->getClient();
        }
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $user_id = $this->Auth->user("id");
        if ($user_id === null) {
            $user_id = -1;
        } else {
            $user = $this->Users->find()->where(["id"=>$user_id])->first();
            if (isset($user)) {
                $this->set("user", $user);
            } else {
                $this->Auth->logout();
                $this->request->getSession()->clear();
            }
        }
    }

    public function isAuthorized($user) {
        return true;
    }

    protected function randString() {
        $string = "";
        for ($i = 0; $i < 100; $i++) {
            $string .= chr(rand(0, 126));
        }
        return $string;
    }

    protected function setActivity(){
        $this->loadModel("Activity");
        $activity = $this->Activity->find()->contain(["Users"])->order(["time" => "DESC"])->limit(6);
        $this->set("activity", $activity);
    }

    protected function addPost($save_images = false, $post_type = Post::TYPE_WALL){
        $this->loadModel("Posts");
        $this->loadModel("Users");
        $this->loadModel("UserPostNotifications");
        $user = $this->Users->get($this->Auth->user("id"));
        $post = $this->Posts->newEntity(["associated" => ["PostImages","GroupPosts","UserPostNotifications","Polls"]]);

        if($save_images && $_FILES != []){
            $this->loadModel("PostImages");
            $post->post_images = [];
            foreach($_FILES as $file) {
                $image = $this->PostImages->newEntity();
                $path = $this->uploadImage($file);
                $image->image = $path;
                if($path != "") {
                    $post->post_images[] = $image;
                }
            }
        }

        if(isset($_POST["_group_id"])){
            $this->loadModel("GroupPosts");
            $group_post = $this->GroupPosts->newEntity();
            $group_post->group_id = $_POST["_group_id"];
            $post->group_post = $group_post;
            $post->post_type = Post::TYPE_GROUP;
        }

        if(!isset($post->post_type)){
            $post->post_type = $post_type;
        }

        if($user->auto_post_subscribe){
            $notif = $this->UserPostNotifications->newEntity();
            $notif->notifications = true;
            $notif->user_id = $user->id;
            $post->user_post_notifications = [$notif];
        }

        if(isset($_POST["question"]) && $_POST["question"] !== ""){
            $this->loadModel("Polls");
            $this->loadModel("PollOptions");
            $poll = $this->Polls->newEntity(["associated" => ["PollOptions"]]);
            $poll->question = $_POST["question"];
            $poll->user_add_options = isset($_POST["user_add_options"]) ? $_POST["user_add_options"] === "on" : false;
            $poll->multi = $_POST["multi"] === "1";
            $poll->expires = isset($_POST["expires"]) && $_POST["expires"] !== "" ? strtotime($_POST["expires"]) : -1;
            $poll->redo = isset($_POST["redo"]) ? $_POST["redo"] === "on" : false;
            $poll->poll_options = [];

            for($i = 1; isset($_POST["option" . $i]); $i++){
                $optionText = $_POST["option" . $i];
                if($optionText !== ""){
                    $option = $this->PollOptions->newEntity();
                    $option->option_name = $optionText;
                    $poll->poll_options[] = $option;
                }
            }

            $post->polls = [$poll];
        }

        if ($this->request->is('post')){
            $post->content = $_POST["content"];
            $post->user_id = $this->Auth->user(['id']);
            $post->created_time = time();
            if ($this->Posts->save($post)) {
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $post->id,
                    "action" => Activity::POST_ADD,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);
                if($post->post_type == Post::TYPE_GROUP) {
                    $data = [
                        "post_id" => $post->id,
                        "group_id" => $_POST["_group_id"],
                    ];
                    $this->QueuedJobs->createJob("GroupPostNotification", $data);
                }
                return $post->id;
            }
        }
        return false;
    }

    protected function googleConfig(){
        $gClient = new \Google_Client();

        $gClient->setClientId(Configure::read("GoogleApplication.client_id"));
        $gClient->setClientSecret(Configure::read("GoogleApplication.client_secret"));
        $gClient->setApplicationName("Yordas Social");
        $gClient->setRedirectUri(substr(BASE_URL,0,strlen(BASE_URL)-1) . Router::url(["controller"=>"Users","action"=>"login"]));
        $gClient->addScope("profile openid email");// https://www.googleapis.com/auth/calendar"); //https://www.googleapis.com/auth/calendar calender scope
        $gClient->setAccessType("offline");
        $gClient->setApprovalPrompt("force");
        return $gClient;
    }

    protected function isAdmin(){
        $this->loadModel("Users");
        $user_id = $this->Auth->user("id");
        $user = $this->Users->get($user_id);

        return $user->Permissions == 100;
    }

    protected function getClient(){
        $session = $this->request->getSession();

        if($this->Cookie->check("access_token")) { //isset($_COOKIE["access_token"])
            $access_token = ($this->Cookie->read("access_token"));//$_COOKIE["access_token"];
            $access_token = json_encode($access_token);

            $key = Configure::read("EncryptionKey");
            //debug($access_token);
            $user = $this->Users->find()->where(["access_token" => $access_token])->first();
//        $user = $this->Users->get($this->Auth->user("id"));
            if ($user) {
                $gClient = $this->googleConfig();
                $gClient->setAccessToken(json_decode($access_token, true));
                if ($gClient->isAccessTokenExpired()) { //Update if access token has expired
                    $access_token = $gClient->fetchAccessTokenWithRefreshToken($user->refresh_token);
                    $user->access_token = json_encode($access_token);
                    if ($this->Users->save($user)) {
                        $session->write("access_token", $access_token);
                        //setcookie("access_token", json_encode($access_token), time() + (86400 * 30), "/");
                        $this->Cookie->write("access_token",json_encode($access_token));
                        $this->Auth->setUser($user->toArray());
                        $this->updateUser();
                    }
                } else { //If not expired, we know who the user is.
                    if ($this->Auth->user("id") != $user->id) {
                        $this->Auth->setUser($user->toArray());
                    }
                    $this->updateUser();
                }
                return $gClient;
            }
        }
        return null;
    }

    protected function uploadImage($file) {
        $arrayExt = explode(".", $file["name"]);
        $extension = strtolower(end($arrayExt));
        $allowedExtensions = ["jpg", "png", "tiff", "jpeg"];
        //debug($file);
        if ($file["size"] > 15000000) { //File limit of 15MB
            $this->Flash->error("File size exceeds 15MB");
        } elseif (!(in_array($extension, $allowedExtensions))) {
            $this->Flash->error("File is not an image");
        } else {
            $extension = substr($file["type"], 6);
            $file_name = uniqid("", true);
            $path = '/img/users/';
            $full_path = $path . $file_name . "." . $extension;
            move_uploaded_file($file["tmp_name"], WWW_ROOT . $full_path);
            return $full_path;
        }
        return "";
    }

}
