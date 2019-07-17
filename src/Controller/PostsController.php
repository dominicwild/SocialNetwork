<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Activity;
use App\Model\Entity\Post;
use App\Model\Table\ActivityTable;
use App\Model\Table\CommentsTable;
use App\Model\Table\GroupsTable;
use App\View\Helper\MiscellaneousHelper;
use Aura\Intl\Exception;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use phpseclib\File\ASN1\Element;
use Queue\Model\Entity\QueuedJob;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\GroupPostsTable $GroupPosts
 * @property QueuedJob $QueuedJobs
 * @property ActivityTable $Activity
 * @property GroupsTable $Groups
 * @property CommentsTable $Comments
 *
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {

        $this->paginate = [
            'contain' => ['Users']
        ];
        $posts = $this->paginate($this->Posts);

        $this->set(compact('posts'));
    }

    public function home(){
        $this->setActivity();
        $user_id = $this->Auth->user("id");
        if(isset($_GET["id"])){
            $link_post = $this->checkPost($_GET["id"]);
        } elseif(isset($_GET["comment_id"])){
            $link_post = $this->checkComment($_GET["comment_id"]);
        }
        $this->paginate = [
            'contain' => ['Users', "Comments"]
        ];
        $posts = $this->paginate($this->Posts);
        $post = $this->Posts->newEntity();

        $this->set(compact("posts"));
        $this->set("post", $post);

        $findPost = $this->Posts
            ->find("RenderContent",["user" => $user_id])
            ->where(["Posts.post_type" => Post::TYPE_WALL])
            ->order(["Posts.created_time" => "DESC"])->limit(20);

        if(isset($link_post)){
            $findPost->where(["Posts.id !=" => $link_post->id]);
        }

        $this->set("outputPost", $findPost);
    }

    public function getPost(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $id = $_POST["id"];
            $post = $this->Posts->find("RenderContent", ["user_id" => $id])->where(["Posts.id" => $id])->first();
            $this->set("post",$post);
            return $this->render("/Element/post");
        }
        return $this->response->withStringBody("false");
    }

    private function checkComment($id){
        try {
            $this->loadModel("Comments");
            $comment = $this->Comments->get($id);
            if ($comment) {
                $link_post = $this->Posts->get($comment->post_id, ["contain" => ["Users", "PostImages", "Comments" => ["Users", "sort" => ["Comments.created_time" => "ASC"]]]]);
                $this->set("link_post", $link_post);
                $this->set("link_comment_id", $id);
                return $link_post;
            } else {
                $this->Flash->error("That comment does not exist or was deleted.");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("That comment does not exist or was deleted.");
        }
    }

    private function checkPost($id){
        try {
//        $post = $this->Posts->get($id,["contain" => ["Users", "PostImages","Comments" => ["Users"]]]);
            $post = $this->Posts->find("RenderContent",["user" => $this->Auth->user("id")])->where(["Posts.id" => $id])->first();
            if($post) {
                $this->set("link_post", $post);
                return $post;
            } else {
                $this->Flash->error("That post does not exist or was deleted.");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("That post does not exist or was deleted.");
        }
    }

    public function addComments(){
//        $users = TableRegistry::getTableLocator()->get("Users");
//        $numUsers = $users->find()->count();
//
//        for($i = 0;$i < 100; $i++){
//            $newPost = $this->Posts->newEntity();
//            $randContent = $this->randString();
//            $randUser = rand(1,$numUsers);
//            $newPost->user_id = $randUser;
//            $newPost->content = $randContent;
//            $this->Posts->save($newPost);
//        }
    }

    public function loadMorePosts(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")) {

            $last_id = $_POST["last_id"];
            $last_post = $this->Posts->get($last_id);
            $last_time = $last_post->created_time;

            $where_conditions = [
                "Posts.created_time <=" => $last_time,
                "Posts.id !=" => $last_post->id,
            ];

            if(isset($_POST["post_type"])){
                $where_conditions["Posts.post_type"] = $_POST["post_type"];
            } else {
                $where_conditions["Posts.post_type"] = Post::TYPE_WALL;
            }

            if(isset($_POST["id"])){
                $where_conditions["Posts.user_id"] = $_POST["id"];
            }

            $query = $this->Posts
                ->find("RenderContent", ["user" => $this->Auth->user("id")])
                ->where($where_conditions)
                ->limit(20);

            $this->set("posts", $query);
        }
    }

    public function loadMoreComments(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){ //define post for comments to render
            $post_id = $_POST["postId"];
            $commentCount = $_POST["commentCount"];

            $post = $this->Posts->find("RenderContent")->where(["Posts.id" => $post_id]);
            $renderList = $post->first()->comments;

            array_splice($renderList,$commentCount*-1);

            $this->set("comments",$renderList);
            $this->render("/Element/comments");
        }
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $post = $this->Posts->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('post', $post);
    }

    public function add() {
        $this->viewBuilder()->setLayout("ajax");
        if($id = $this->addPost(true)){
            $post = $this->Posts->find("RenderContent", ["user" => $this->Auth->user("id")])->where(["Posts.id" => $id]);
            $this->set("post",$post->first());
            $this->set("showMoreCommentsBtn",false);
            $this->render("/Element/post");
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit() {
        $this->viewBuilder()->setLayout("ajax");
        $post = $this->Posts->get($_POST["postId"], [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $post->content = $_POST["content"];
            if (($post->user_id == $this->Auth->user("id") || $this->isAdmin()) && $this->Posts->save($post)) {
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $post->id,
                    "action" => Activity::POST_EDIT,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);
                $helper = new MiscellaneousHelper();
                return $this->response->withStringBody($helper->processContent($post->content));
            }
            return $this->response->withStatus(304);
        }
        return $this->response->withStatus(400);
    }

    public function search(){
        $search = isset($_GET["search"]) ? $_GET["search"] : "";
        $search_results = [];

        if(isset($_GET["search"])){
            $search_results = $this->searchResults($search,10);
        }

        $this->set("search",$search);
        $this->set("search_results",$search_results);
    }

    public function loadMoreSearchResults(){
        $this->viewBuilder()->setLayout("ajax");
        $search_results = [];
        if($this->request->is("post")){
            $count = $_POST["count"];
            $search = $_POST["search"];

            $search_results = $this->searchResults($search,10,$count);
        }
        if(sizeof($search_results) == 0){
            return $this->render("/Element/end-search-results");
        }
        $this->set("search_results",$search_results);
        return $this->render("/Element/search-results");
    }

    private function searchResults($search, $limit = INF, $skip = 0){
        $posts = $this->Posts->find("SearchResult",["search" => $search])
            ->toArray();

        $this->loadModel("Events");
        $events = $this->Events->find("SearchResult",["search" => $search] )
            ->toArray();

        $this->loadModel("Groups");
        $groups = $this->Groups->find("SearchResult",["search" => $search])
            ->toArray();

        $results = [$posts, $events, $groups];
        $search_results = [];

        while(sizeof($search_results) < $limit) {
            $highest = 0;
            $highest_index = -1;

            foreach ($results as $index => $result) {
                $element = reset($result);
                if (isset($element->score) && $highest < $element->score) {
                    $highest = $element->score;
                    $highest_index = $index;
                    //debug($highest_index);
                }
            }

            if ($highest_index == -1) {
                break;
            }

            if ($skip > 0) {
                $skip--;
                array_shift($results[$highest_index]);
            } else {
                $search_results[] = array_shift($results[$highest_index]);
            }
        }
        return $search_results;
    }

    public function getImages(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $this->loadModel("PostImages");
            $id = $_POST["id"];
            $post_images = $this->PostImages->find()->where(["post_id" => $id]);
            $this->set("post_images",$post_images->toArray());
            return $this->render("/Element/carouselInner");
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $id = $_POST["postId"];
        $this->viewBuilder()->setLayout("ajax");
        $post = $this->Posts->get($id);
        $user_id = $post->user_id;
        if ($user_id == $this->Auth->user("id") || $this->isAdmin()){
            $this->loadModel("Events");
            $event = $this->Events->find()->where(["post_id" => $post->id])->first();
            if($event != null){
                if($this->Events->delete($event)){
                    if($this->Posts->delete($post)) {
                        return $this->response->withStringBody("true");
                    } else {
                        $this->Events->save($event);
                    }
                }
            } else {
                if($this->Posts->delete($post)) {
                    return $this->response->withStringBody("true");
                }
            }
        }
        return $this->response->withStringBody("false");
    }

    public function userPosts(){
        if(isset($_GET["id"])) {
            $this->setActivity();
            $this->loadModel("Users");
            $user_id = $_GET["id"];
            $posts = $this->Posts->find("RenderContent", ["user" => $this->Auth->user("id")])->where(["Posts.user_id" => $user_id])->limit(20);
            $user = $this->Users->get($user_id);
            if(count($posts->toArray()) == 0){
                //return $this->response->withStatus(404);
            }
            $this->set("outputPost", $posts);
            $this->set("target_user", $user);
        } else {
            return $this->response->withStatus(422);
        }
    }

    public function loadMoreMyPosts(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $exclude_ids = $_POST["displayed_ids"];
            $user_id = $_GET["id"];
            $query = $this->Posts->find("RenderContent")
                ->limit(20)
                ->order(["Posts.created_time" => "DESC"])
                ->where(["Posts.id NOT IN" => $exclude_ids, "Posts.user_id" => $user_id], ['Posts.id' => 'integer[]']);
            $this->set("posts", $query);
            return $this->render("load_more_posts");
        }
    }
}
