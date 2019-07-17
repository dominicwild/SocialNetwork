<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Activity;
use App\View\Helper\MiscellaneousHelper;
use Cake\Http\Response;
use Queue\Model\Table\QueuedJobsTable;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 * @property QueuedJobsTable $QueuedJobs
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users', 'Posts']
        ];
        $comments = $this->paginate($this->Comments);

        $this->set(compact('comments'));
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $comment = $this->Comments->get($id, [
            'contain' => ['Users', 'Posts']
        ]);

        $this->set('comment', $comment);
    }

    public function add() {
        $this->viewBuilder()->setLayout("ajax");
        $comment = $this->Comments->newEntity();
        if ($this->request->is('post')) {
            $comment->user_id = $this->Auth->user("id");
            $comment->content = $_POST["content"];
            $comment->post_id = $_POST["postId"];
            $comment->created_time = time();
            if ($this->Comments->save($comment)) {
                $comment = $this->Comments->get($comment->id, [
                    'contain' => ["Users"]
                ]);
                $process_time = microtime(true);
                $this->loadModel('Queue.QueuedJobs');
                $this->QueuedJobs->createJob('CommentProcess', ["post_id" => $comment->post_id, "time"=>$process_time]);

                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $comment->id,
                    "action" => Activity::COMMENT_ADD,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);

                $this->set("comments", array($comment));
                $this->render("/Element/comments");
                return;
            }
        }
    }

    public function edit() {
        $this->viewBuilder()->getLayout("ajax");
        $id = $_POST["commentId"];
        $comment = $this->Comments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(["post"])) {
            $comment->content = $_POST["content"];
            if (($this->Auth->user("id") == $comment->user_id || $this->isAdmin()) && $this->Comments->save($comment)) {
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "user_id" => $this->Auth->user("id"),
                    "id" => $comment->id,
                    "action" => Activity::COMMENT_EDIT,
                    "time" => microtime(true),
                ];
                $this->QueuedJobs->createJob('LogActivity',$data);
                $helper = new MiscellaneousHelper();
                return $this->response->withStringBody($helper->processContent($comment->content));
            }
            return $this->response->withStatus(304);
        }
        return $this->response->withStatus(400);
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if (($this->Auth->user("id") == $comment->user_id || $this->isAdmin()) && $this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function remove() {
        $id = $_POST["commentId"];
        $this->viewBuilder()->setLayout("ajax");
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        $user_id = $comment->user_id;
        if (($user_id == $this->Auth->user("id") || $this->isAdmin()) && $this->Comments->delete($comment)) {
            return $this->response->withStringBody("true");
        } else {
            return $this->response->withStringBody("false");
        }
    }
}
