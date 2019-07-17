<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Activity;
use App\Model\Entity\Event;
use App\Model\Table\EventsTable;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property EventsTable $Events
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function isAuthorized($user) {
        $action = $this->request->getParam('action');

        if($user){ //If logged in
            if (in_array($action, ['login'])) {
                return false;
            } elseif($user["Permissions"] != 100 && in_array($action, ['admin'])) {
                return false;
            }
            else {
                return true;
            }
        } else {
            if (in_array($action, ["login"])) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['logout',"guestLogin"]);
    }


    public function options(){
        $this->set("user", $this->Users->get($this->Auth->user("id")));
    }

    public function toggleWeekly(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $user = $this->Users->get($this->Auth->user("id"));
            $user->weekly_event_email = $_POST["weekly_event_email"] === "true";
            if($this->Users->save($user)){
                return $this->response->withStringBody("true");
            }
        }
    }

    public function togglePerEvent(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $user = $this->Users->get($this->Auth->user("id"));
            $user->email_per_event = $_POST["email_per_event"] === "true";
            if($this->Users->save($user)){
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function editProfile() {
        $this->viewBuilder()->setLayout("ajax");
        if ($this->request->is(['patch', 'post', 'put'])) {
            $id = $_POST["id"];
            $user = $this->Users->get($id, [
                'contain' => []
            ]);
            $user->gender = $_POST["gender"];
            $user->department = $_POST["department"];
            $user->role = $_POST["role"];
            $user->about_me = $_POST["about_me"];
            $user->status = $_POST["status"];
            $user->FirstName = $_POST["FirstName"] !== "" ? $_POST["FirstName"] : $user->FirstName;
            $user->LastName = $_POST["LastName"];
            if ($id == $this->Auth->user("id") && $this->Users->save($user)) {
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $user->id,
                    "action" => Activity::PROFILE_EDIT,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);

                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("");
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")) {
            $user = $this->Users->get($_POST["id"]);
            if (($this->isAdmin() || $this->Auth->user("id") == $_POST["id"]) && $this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
                return $this->response->withStringBody("true");
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }
        return $this->response->withStringBody("false");
    }

    public function updateAutoSubscribe(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $auto_subscribe = $_POST["auto_subscribe"];
            $user = $this->Users->get($this->Auth->user("id"), ["contain" => "UserPostNotifications"]);
            $user->auto_post_subscribe = $auto_subscribe == "true";

            $this->loadModel("UserPostNotifications");
            $this->loadModel("Posts");
            $posts = $this->Posts->find()->select(["id"])->where(["user_id" => $user->id]);

            foreach ($posts as $post) {
                $notif = $this->UserPostNotifications->newEntity();
                $notif->post_id = $post->id;
                $notif->notifications = $user->auto_post_subscribe;
                $user->user_post_notifications[] = $notif;
            }

            $user->setDirty("user_post_notifications", true);

            if($this->Users->save($user, ["associated" => "UserPostNotifications"])){
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

    public function loginHome(){
        $this->viewBuilder()->setTemplate("login");
        $this->login();
    }

    public function login(){

        $session = $this->request->getSession();
        $gClient = $this->googleConfig();
        $this->set("gClient",$gClient);
        $this->set("auth",$this->Auth);

        if ($this->request->is('post')) {
            if ($this->request->getData("GoogleLogin") != null) {
                return $this->redirect($this->request->getData("GoogleLogin"));
            }
        } elseif (isset($_GET["code"])) { //Handle callback

            $token = $gClient->fetchAccessTokenWithAuthCode($_GET["code"]);
            if (!isset($token["error"])) {

                $oAuth = new \Google_Service_Oauth2($gClient);
                $userData = $oAuth->userinfo_v2_me->get();

                if(!isset($session->read["access_token"])) {
                    $session->write("access_token", $gClient->getAccessToken());
                    $this->Cookie->write("access_token",json_encode($gClient->getAccessToken()));
                    //setcookie("access_token", json_encode($gClient->getAccessToken()), time() + (86400 * 30), "/");
                }

                $user = $this->addUser($userData);

                if ($user) {
                    $this->Auth->setUser($user->toArray());
                    $this->updateUser();
                    $session->write("user", $user);
                    //return $this->redirect(["controller" => "Posts",'action' => 'home']);
                }
            }//isset($_COOKIE["access_token"])
        } elseif ($this->Cookie->check("access_token")) {
            if($this->Auth->user() == null){
                $this->getClient();
                //return $this->redirect($this->referer());
            } else {
                $this->getClient();
            }
        }
    }

    public function guestLogin(){
        $this->viewBuilder()->setTemplate("login");
        $user = $this->Users->find()->where(["Email" => "socialdominicwild@gmail.com"])->first();
        if($user){
            $this->Auth->setUser($user->toArray());
            $this->updateUser();
            $this->request->getSession()->write("user", $user);
        } else {
            $this->Flash->error("Guest account not initialized. Please try again later or contact the network administrator.");
        }
        $this->login();
    }

    public function changeCommentNotifSettings(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $user = $this->getCurrentUser();
            $user->comment_notification_option = $_POST["comment_notification_option"];
            if($this->Users->save($user)){
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

    private function getCurrentUser(){
        return $this->Users->get($this->Auth->user("id"));
    }

    public function changeProfilePicture(){
        if($this->request->is("post")){
            if(isset($_FILES["file"])){
                if($this->Auth->user("id") != $_POST["id"]) {
                    $this->Flash->error("You may only change your own profile picture");
                } else {
                    $file = $_FILES["file"];
                    $full_path = $this->uploadImage($file);
                    if(strcmp($full_path , "") != 0) {
                        $user = $this->Users->get($_POST["id"]);
                        $user->profile_image = $full_path;

                        if (!$this->Users->save($user)) {
                            $this->Flash->error("Save was unsuccessful");
                        }
                    }
                }
            }
        }
        return $this->redirect($this->request->referer());
    }

    private function addUser($userData) {
        $user = $this->Users->find()->where(["google_id" => $userData["id"]])->first();
        if ($user) {
//            $user->access_token = $_SESSION['access_token']['access_token'];
            $user->access_token = json_encode($_SESSION['access_token']);
            $this->Users->save($user);
            return $user;
        } else {
            $user = $this->Users->newEntity();
        }

        $image = file_get_contents($userData->getPicture());
        $full_path = $this->imageFromURL($image, "jpg");

        //Terminates if not yordas group email
//        if(substr($userData["email"], strrpos($userData["email"], '@') + 1) !== "yordasgroup.com"){
//            $this->Flash->error("This email does not have the yordas group domain.");
//            return null;
//        }

        $user->profile_image = $full_path;
        $user->Email = $userData["email"];
        $user->FirstName = $userData["givenName"];
        $user->LastName = $userData["familyName"];
        $user->gender = $userData["gender"];
        $user->google_id = $userData["id"];
        $user->refresh_token = $_SESSION['access_token']['refresh_token'];
//        $user->access_token = $_SESSION['access_token']['access_token'];
        $user->access_token = json_encode($_SESSION['access_token']);
        $user->weekly_event_email = true;
        $user->email_per_event = false;
        $user->Permissions = 0;
        if($this->Users->save($user)){
            return $user;
        } else {
            return null;
        }
    }

    private function imageFromURL($file, $extension){
        $file_name = uniqid("",true);
        $path = '/img/users/';
        $full_path = $path . $file_name . "." . $extension;
        //file_put_contents("webroot" . $full_path, $file);
        file_put_contents(WWW_ROOT . $full_path, $file);
        return $full_path;
    }

    public function logout(){
        $this->getRequest()->getSession()->destroy();
        $this->Auth->logout();
        $this->set("logged_in",false);
        //$this->updateUser();
    }

    public function profile($id = null){
        $id = $_GET["id"];
        $user = $this->Users->get($id);
        $this->set("user",$user);
    }

    public function signIn(){
        $id_token = $_POST["idtoken"];
        $gClient = $this->googleConfig2();
        $payload = $gClient->verifyIdToken($id_token);
        if ($payload) {
            $userid = $payload['sub'];
            return $this->response->withStringBody($userid);
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            return $this->response->withStringBody("Invalid token");
        }
    }

    public function userList(){
        $this->setActivity();
        $users = $this->Users->find("all")->where(["id !=" => $this->Auth->user("id")]);
        $this->set("users", $users);
    }

    public function admin(){
        $users = $this->Users->find("all")->contain("Ambassadors");
        $this->set("users", $users);
    }

    public function updateAdmin(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post") && $this->isAdmin()){
            $user_id = $_POST["id"];
            $isAdmin = $_POST["admin"] === "true";

            if($isAdmin){
                $permission = 100;
            } else {
                $permission = 0;
            }

            $user = $this->Users->get($user_id);
            $user->Permissions = $permission;
            if($this->Users->save($user)){
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

}
