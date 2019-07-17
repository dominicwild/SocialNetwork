<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * ReportedPosts Controller
 *
 * @property \App\Model\Table\ReportedPostsTable $ReportedPosts
 *
 * @method \App\Model\Entity\ReportedPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportedPostsController extends AppController {

    public function isAuthorized($user) {
        $action = $this->request->getParam('action');

        if(in_array($action,["view"])){
            return $this->isAdmin();
        }
        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index(){
        $this->paginate = [
            'contain' => ['Users', 'Posts']
        ];
        $reportedPosts = $this->paginate($this->ReportedPosts);

        $this->set(compact('reportedPosts'));
    }

    /**
     * View method
     *
     * @param string|null $id Reported Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(){
        $reports = $this->ReportedPosts->find("RenderContent",["user_id" => $this->Auth->user("id")])->limit(10);
        $this->set("reports",$reports);
    }

    public function loadMoreReportedPosts(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post") && $this->isAdmin()){
            $last_id = isset($_POST["last_id"]) ? $_POST["last_id"] : -1;
            $reports = $this->ReportedPosts->find("RenderContent")->where(["ReportedPosts.id <" => $last_id])->limit(10)->toArray();
            $this->set("reports",$reports);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        $this->viewBuilder()->setLayout("ajax");

        if ($this->request->is('post')) {
            $reportedPost = $this->ReportedPosts->newEntity();
            $reportedPost->user_id = $this->Auth->user("id");
            $reportedPost->post_id = $_POST["post_id"];
            $reportedPost->reason = $_POST["reason"];
            $reportedPost->date = time();

            if($this->ReportedPosts->save($reportedPost)){
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "report_id" => $reportedPost->id,
                ];
                $this->QueuedJobs->createJob('ReportedPostEmail',$data);
                return $this->response->withStringBody("true");
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Reported Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $reportedPost = $this->ReportedPosts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportedPost = $this->ReportedPosts->patchEntity($reportedPost, $this->request->getData());
            if ($this->ReportedPosts->save($reportedPost)) {
                $this->Flash->success(__('The reported post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reported post could not be saved. Please, try again.'));
        }
        $users = $this->ReportedPosts->Users->find('list', ['limit' => 200]);
        $posts = $this->ReportedPosts->Posts->find('list', ['limit' => 200]);
        $this->set(compact('reportedPost', 'users', 'posts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reported Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $reportedPost = $this->ReportedPosts->get($id);
        if ($this->ReportedPosts->delete($reportedPost)) {
            $this->Flash->success(__('The reported post has been deleted.'));
        } else {
            $this->Flash->error(__('The reported post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
