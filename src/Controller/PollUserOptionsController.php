<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PollUserOptions Controller
 *
 * @property \App\Model\Table\PollUserOptionsTable $PollUserOptions
 *
 * @method \App\Model\Entity\PollUserOption[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PollUserOptionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'PollOptions']
        ];
        $pollUserOptions = $this->paginate($this->PollUserOptions);

        $this->set(compact('pollUserOptions'));
    }

    /**
     * View method
     *
     * @param string|null $id Poll User Option id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pollUserOption = $this->PollUserOptions->get($id, [
            'contain' => ['Users', 'PollOptions']
        ]);

        $this->set('pollUserOption', $pollUserOption);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pollUserOption = $this->PollUserOptions->newEntity();
        if ($this->request->is('post')) {
            $pollUserOption = $this->PollUserOptions->patchEntity($pollUserOption, $this->request->getData());
            if ($this->PollUserOptions->save($pollUserOption)) {
                $this->Flash->success(__('The poll user option has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The poll user option could not be saved. Please, try again.'));
        }
        $users = $this->PollUserOptions->Users->find('list', ['limit' => 200]);
        $pollOptions = $this->PollUserOptions->PollOptions->find('list', ['limit' => 200]);
        $this->set(compact('pollUserOption', 'users', 'pollOptions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Poll User Option id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pollUserOption = $this->PollUserOptions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pollUserOption = $this->PollUserOptions->patchEntity($pollUserOption, $this->request->getData());
            if ($this->PollUserOptions->save($pollUserOption)) {
                $this->Flash->success(__('The poll user option has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The poll user option could not be saved. Please, try again.'));
        }
        $users = $this->PollUserOptions->Users->find('list', ['limit' => 200]);
        $pollOptions = $this->PollUserOptions->PollOptions->find('list', ['limit' => 200]);
        $this->set(compact('pollUserOption', 'users', 'pollOptions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Poll User Option id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pollUserOption = $this->PollUserOptions->get($id);
        if ($this->PollUserOptions->delete($pollUserOption)) {
            $this->Flash->success(__('The poll user option has been deleted.'));
        } else {
            $this->Flash->error(__('The poll user option could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
