<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserPostNotifications Controller
 *
 * @property \App\Model\Table\UserPostNotificationsTable $UserPostNotifications
 *
 * @method \App\Model\Entity\UserPostNotification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserPostNotificationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Posts']
        ];
        $userPostNotifications = $this->paginate($this->UserPostNotifications);

        $this->set(compact('userPostNotifications'));
    }

    /**
     * View method
     *
     * @param string|null $id User Post Notification id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userPostNotification = $this->UserPostNotifications->get($id, [
            'contain' => ['Users', 'Posts']
        ]);

        $this->set('userPostNotification', $userPostNotification);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $userPostNotification = $this->UserPostNotifications->newEntity();
        if ($this->request->is('post')) {
            $userPostNotification = $this->UserPostNotifications->patchEntity($userPostNotification, $this->request->getData());
            if ($this->UserPostNotifications->save($userPostNotification)) {
                $this->Flash->success(__('The user post notification has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user post notification could not be saved. Please, try again.'));
        }
        $users = $this->UserPostNotifications->Users->find('list', ['limit' => 200]);
        $posts = $this->UserPostNotifications->Posts->find('list', ['limit' => 200]);
        $this->set(compact('userPostNotification', 'users', 'posts'));
    }

    public function modifyNotification() {
        $this->viewBuilder()->setLayout("ajax");
        if ($this->request->is('post')) {
            $userPostNotification = $this->UserPostNotifications->newEntity();
            $userPostNotification->user_id = $this->Auth->user("id");
            $userPostNotification->post_id = $_POST["post_id"];
            $userPostNotification->notifications = $_POST["notifications"] === "true";
            if ($this->UserPostNotifications->save($userPostNotification)) {
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

    /**
     * Delete method
     *
     * @param string|null $id User Post Notification id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userPostNotification = $this->UserPostNotifications->get($id);
        if ($this->UserPostNotifications->delete($userPostNotification)) {
            $this->Flash->success(__('The user post notification has been deleted.'));
        } else {
            $this->Flash->error(__('The user post notification could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
