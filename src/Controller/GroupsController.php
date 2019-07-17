<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Activity;
use App\Model\Entity\Event;
use App\Model\Entity\Group;
use App\Model\Entity\GroupMember;
use App\Model\Entity\GroupPost;
use App\Model\Table\EventsTable;
use App\Model\Table\GroupMembersTable;
use App\Model\Table\UsersTable;
use App\View\Helper\MiscellaneousHelper;
use Aura\Intl\Exception;
use Cake\ORM\Query;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 * @property \App\Model\Table\GroupMembersTable $GroupMembers
 * @property \App\Model\Table\GroupPostsTable $GroupPosts
 * @property UsersTable $Users
 * @property EventsTable $Events
 *
 * @method \App\Model\Entity\Group[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $user_id = $this->Auth->user("id");
        $groups = $this->Groups->find("GroupCard", ["user" => $user_id])->toArray();

        usort($groups, function($a, $b) {
            return -($a->recent_time - $b->recent_time);
        });

        $this->set(compact('groups'));
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        if(isset($_GET["id"])){
            $this->loadModel("GroupMembers");
            $id = $_GET["id"];
            $user_id = $this->Auth->user("id");
            $group = $this->Groups->find("RenderContent",["group_id" => $id, "user" => $user_id]);

            if(!($group->first())){
                $this->response->withStatus(404, "Group not found.");
                return;
            }

            if(isset($_GET["comment_id"])){
                $post = $this->getGroupComment($_GET["comment_id"]);
            } elseif(isset($_GET["post_id"])){
                $post = $this->getGroupPost($_GET["post_id"]);
            }

            $group_members = $this->GroupMembers->find()->contain(['Users'])->where(["group_id" => $id]);

            $this->loadModel("GroupPosts");
            $group_posts = $this->GroupPosts->find("RenderContent",["user" => $user_id, "group_id" => $id]);

            if(isset($post)){
                $group_posts = $group_posts->where(["GroupPosts.post_id != " => $post->id]);
            }
            $group_posts = $group_posts->toArray();

            $this->set('group', $group->toArray()[0]);
            $this->set('group_posts', $group_posts);
            $this->set('group_members', $group_members);
        } else {
            $this->response->withStatus(404, "Group not found.");
            return;
        }
    }

    private function getGroupPost($id){
        try {
            $this->loadModel("Posts");
            $post = $this->Posts->get($id, ["contain" => ["Users", "PostImages", "Comments" => ["Users"]]]);

            if ($post) {
                $this->set("link_post", $post);
                return $post;
            } else {
                $this->Flash->error("This group post either doesn't exist or was deleted.");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("This group post either doesn't exist or was deleted.");
        }
    }

    private function getGroupComment($id){
        try {
            $this->loadModel("Posts");
            $post = $this->Posts->get($_GET["post_id"],["contain" => ["Users", "PostImages","Comments" => ["Users"]]]);

            if($post){
                $this->set("link_post", $post);
                $this->set("link_comment_id", $id);
                return $post;
            } else {
                $this->Flash->error("This group post either doesn't exist or was deleted.");
            }
        } catch (\RuntimeException $e){
            $this->Flash->error("This group post either doesn't exist or was deleted.");
        }
    }

    public function loadMore(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $this->loadModel("GroupPosts");
            $this->loadModel("Posts");
            $this->loadModel("Events");
            $id = $_POST["group_id"];
            $last_post_id = $_POST["last_post_id"];

            $user_id = $this->Auth->user("id");

            $group = $this->Groups
                ->find("RenderContent", [
                    "group_id" => $id,
                    "user" => $user_id,
                    "last_id" => $last_post_id
                    ]
                )
                ->limit(10);
            $group_posts = $this->GroupPosts
                ->find("RenderContent", ["group_id" => $id, "user" => $user_id, "last_id" => $last_post_id])
                ->limit(10);

            $this->set("events",$group->toArray()[0]->events);
            $this->set("group_posts",$group_posts->toArray());

            $this->render("/Element/group-content");
        }
    }

    public function userGroups(){
        if($this->request->is("GET")) {
            $this->loadModel("GroupMembers");
            $this->loadModel("Users");
            $user_id = $_GET["id"];
            $target_user = $this->Users->get($user_id);

            $results = $this->GroupMembers
                ->find("UserGroups", [
                    "user" => $this->Auth->user("id"),
                    "id" => $user_id,
                    "event_limit" => 10,
                    "group_post_limit" => 10,
                ])
                ->formatResults($this->formatUserGroups($user_id))
                ->toArray();

            $this->set("groups", $results["groups"]);
            $this->set("events", $results["events"]);
            $this->set("group_posts", $results["group_posts"]);
            $this->set("target_user", $target_user);
        } else {
            $this->response->withStatus("404");
        }
    }

    public function loadMoreUserGroup() {
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $this->loadModel("GroupMembers");
            $user_id = $_POST["id"];

            $results = $this->GroupMembers
                ->find("UserGroups", [
                    "user" => $this->Auth->user("id"),
                    "id" => $user_id,
                    "event_limit" => 10,
                    "group_post_limit" => 10,
                    "last_post_id" => $_POST["last_post_id"],
                    "last_event_id" => $_POST["last_event_id"],
                ])
                ->formatResults($this->formatUserGroups($user_id))
                ->toArray();

            $this->set("events", $results["events"]);
            $this->set("group_posts", $results["group_posts"]);
            return $this->render("/Element/group-content");
        }
    }

    private function formatUserGroups($user_id){
        return function (\Cake\Collection\CollectionInterface $results) use ($user_id) {
            $groups = [];
            $events = [];
            $group_posts = [];
            foreach ($results as $member) {
                $group = $member->group;
                $groups[] = $group;
                $events = array_merge($events, $group->events);
                $group_posts = array_merge($group_posts, $group->group_posts);
            }

            usort($events, function (Event $a, Event $b) {
                return -($a->post->created_time - $b->post->created_time);
            });


            usort($group_posts, function (GroupPost $a, GroupPost $b) {
                return -($a->post->created_time - $b->post->created_time);
            });

            usort($groups, function (Group $a, Group $b) {
                return -($a->recent_time - $b->recent_time);
            });

            $newResults = [
                "groups" => $groups,
                "events" => $events,
                "group_posts" => $group_posts,
            ];

            return $newResults;
        };
    }

    public function changeGroupPicture(){
        if($this->request->is("post") && isset($_FILES["file"])) {
            $path = $this->uploadImage($_FILES["file"]);
            $group = $this->Groups->get($_POST["id"]);
            $group->image = $path;
            $group->image_by = $this->Auth->user("id");
            if($path !== "" && $this->Groups->save($group)){
                $this->Flash->success("Image saved successfully.");
            }
            return $this->redirect($this->referer());
        }
        return $this->redirect($this->referer());
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $group = $this->Groups->newEntity(["associated" => "GroupMembers"]);
        if ($this->request->is('post')) {
            $this->loadModel("GroupMembers");
            $group_member = $this->GroupMembers->newEntity();
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            $full_path = $this->uploadImage($_FILES["image"]);
            if (strcmp($full_path, "") == 0) {
                $this->Flash->error("Failed to upload image.");
                return $this->redirect($this->referer());
            }
            $group->image = $full_path;
            $user_id = $this->Auth->user("id");
            $group->image_by = $user_id;
            $group->description_by = $user_id;
            $group_member->user_id = $user_id;

            $group->group_members = [$group_member];
            $group->setDirty('group_members', true);
            if ($this->Groups->save($group, ["associated" => "GroupMembers"])) {
                $this->Flash->success(__('The group has been saved.'));
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $group->id,
                    "action" => Activity::GROUP_ADD,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity', $data);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The group could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'add']);
            }
        }
        $this->set(compact('group'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit() {
        $this->viewBuilder()->setLayout("ajax");
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->get($_POST["id"]);
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            $group->description_by = $this->Auth->user("id");
            if ($this->Groups->save($group)) {
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $group->id,
                    "action" => Activity::GROUP_EDIT,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);

                $helper = new MiscellaneousHelper();
                return $this->response->withStringBody($helper->processContent($group->description));
            }
            return $this->response->withStatus("404");
        }
        $this->set(compact('group'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")) {
            $id = $_POST["id"];
            $group = $this->Groups->get($id);
            if ($this->isAdmin() && $this->Groups->delete($group)) {
                $this->Flash->success(__('The group has been deleted.'));
                return $this->response->withStringBody("true");
            } else {
                $this->Flash->error(__('The group could not be deleted. Please, try again.'));
                return $this->response->withStringBody("false");
            }
        }
        return $this->response->withStringBody("");
    }
}
