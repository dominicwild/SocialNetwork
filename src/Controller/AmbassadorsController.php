<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ambassadors Controller
 *
 * @property \App\Model\Table\AmbassadorsTable $Ambassadors
 *
 * @method \App\Model\Entity\Ambassador[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AmbassadorsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $ambassadors = $this->paginate($this->Ambassadors);

        $this->set(compact('ambassadors'));
    }

    /**
     * View method
     *
     * @param string|null $id Ambassador id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $ambassador = $this->Ambassadors->get($id, [
            'contain' => []
        ]);

        $this->set('ambassador', $ambassador);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $ambassador = $this->Ambassadors->newEntity();
        if ($this->request->is('post')) {
            $ambassador = $this->Ambassadors->patchEntity($ambassador, $this->request->getData());
            if ($this->Ambassadors->save($ambassador)) {
                $this->Flash->success(__('The ambassador has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ambassador could not be saved. Please, try again.'));
        }
        $this->set(compact('ambassador'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ambassador id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $ambassador = $this->Ambassadors->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ambassador = $this->Ambassadors->patchEntity($ambassador, $this->request->getData());
            if ($this->Ambassadors->save($ambassador)) {
                $this->Flash->success(__('The ambassador has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ambassador could not be saved. Please, try again.'));
        }
        $this->set(compact('ambassador'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Ambassador id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $ambassador = $this->Ambassadors->get($id);
        if ($this->Ambassadors->delete($ambassador)) {
            $this->Flash->success(__('The ambassador has been deleted.'));
        } else {
            $this->Flash->error(__('The ambassador could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function updateAmbassador(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post") && $this->Auth->user("Permissions") === 100){
            $id = $_POST["id"];
            $isAmbassador = $_POST["ambassador"] === "true";

            $ambassador = $this->Ambassadors->find("all")->where(["user_id" => $id])->first();

            if($ambassador != null && ($isAmbassador == false)){ //If result is in database
                $this->Ambassadors->delete($ambassador);
                $this->log("Deleted");
            } elseif ($ambassador == null && $isAmbassador == true) {
                $ambassador = $this->Ambassadors->newEntity();
                $ambassador->user_id = $id;
                $ambassador->remind_time = time() + 60*60*24*7;
                if($this->Ambassadors->save($ambassador)){
                    $this->loadModel('Queue.QueuedJobs');
                    $data = [
                        "user_id" => $ambassador->user_id,
                        "remind_time" => $ambassador->remind_time,
                    ];
                    //$this->QueuedJobs->createJob('AmbassadorReminder',$data,["notBefore" => $ambassador->remind_time]);
                    $this->QueuedJobs->createJob('AmbassadorReminder',$data);
                    return $this->response->withStringBody("true");
                } else {
                    $this->response->withStatus("500");
                }
            }
            return $this->response->withStringBody("true");
        }
        $this->response->withStatus("400");
    }
}
