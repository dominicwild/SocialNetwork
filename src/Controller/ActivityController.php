<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Activity Controller
 *
 * @property \App\Model\Table\ActivityTable $Activity
 *
 * @method \App\Model\Entity\Activity[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ActivityController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $activity = $this->paginate($this->Activity);

        $this->set(compact('activity'));
    }

    public function scrollActivity(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $direction = $_POST["direction"];
            $id = $_POST["id"];
            $exclude_ids = $_POST["exclude_ids"];
            $activity = $this->Activity->get($id);
            $renderActivity = "";
            $query = $this->Activity->find()->contain(["Users"])->where(["Activity.id NOT IN" => $exclude_ids],["Activity.id" => "integer[]"])->limit(6);
            if($direction > 0){
                $renderActivity = $query->where(["time <=" => $activity->time])->order(["time" => "DESC"]);
            } else {
                $renderActivity = $query->where(["time >=" => $activity->time])->order(["time" => "ASC"]);
                $this->set("reverse",true);
            }
            $this->set("renderActivity", $renderActivity);
        } else {
            return $this->response->withStringBody("");
        }

    }

    /**
     * View method
     *
     * @param string|null $id Activity id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $activity = $this->Activity->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('activity', $activity);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $activity = $this->Activity->newEntity();
        if ($this->request->is('post')) {
            $activity = $this->Activity->patchEntity($activity, $this->request->getData());
            if ($this->Activity->save($activity)) {
                $this->Flash->success(__('The activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The activity could not be saved. Please, try again.'));
        }
        $users = $this->Activity->Users->find('list', ['limit' => 200]);
        $this->set(compact('activity', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Activity id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $activity = $this->Activity->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $activity = $this->Activity->patchEntity($activity, $this->request->getData());
            if ($this->Activity->save($activity)) {
                $this->Flash->success(__('The activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The activity could not be saved. Please, try again.'));
        }
        $users = $this->Activity->Users->find('list', ['limit' => 200]);
        $this->set(compact('activity', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Activity id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $activity = $this->Activity->get($id);
        if ($this->Activity->delete($activity)) {
            $this->Flash->success(__('The activity has been deleted.'));
        } else {
            $this->Flash->error(__('The activity could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
