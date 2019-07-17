<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GroupMembers Controller
 *
 * @property \App\Model\Table\GroupMembersTable $GroupMembers
 *
 * @method \App\Model\Entity\GroupMember[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupMembersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Groups']
        ];
        $groupMembers = $this->paginate($this->GroupMembers);

        $this->set(compact('groupMembers'));
    }

    /**
     * View method
     *
     * @param string|null $id Group Member id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $groupMember = $this->GroupMembers->get($id, [
            'contain' => ['Users', 'Groups']
        ]);

        $this->set('groupMember', $groupMember);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->viewBuilder()->setLayout("ajax");
        $groupMember = $this->GroupMembers->newEntity();
        if ($this->request->is('post')) {
            $groupMember->group_id = $_POST["groupId"];
            $groupMember->user_id = $this->Auth->user("id");
            if ($this->GroupMembers->save($groupMember)) {
                return $this->response->withStringBody("true");
            } else {
                return $this->response->withStringBody("false");
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Group Member id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $groupMember = $this->GroupMembers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $groupMember = $this->GroupMembers->patchEntity($groupMember, $this->request->getData());
            if ($this->GroupMembers->save($groupMember)) {
                $this->Flash->success(__('The group member has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group member could not be saved. Please, try again.'));
        }
        $users = $this->GroupMembers->Users->find('list', ['limit' => 200]);
        $groups = $this->GroupMembers->Groups->find('list', ['limit' => 200]);
        $this->set(compact('groupMember', 'users', 'groups'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group Member id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")) {
            $this->request->allowMethod(['post', 'delete']);
            $groupMember = $this->GroupMembers->find()->where(["group_id" => $_POST["groupId"], "user_id" => $this->Auth->user("id")])->first();
            if ($groupMember != null && $this->GroupMembers->delete($groupMember)) {
                return $this->response->withStringBody("true");
            } else {
                return $this->response->withStringBody("false");
            }
        }
    }
}
