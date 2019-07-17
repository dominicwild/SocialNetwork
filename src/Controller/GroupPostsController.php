<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GroupPosts Controller
 *
 * @property \App\Model\Table\GroupPostsTable $GroupPosts
 *
 * @method \App\Model\Entity\GroupPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupPostsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Groups', 'Posts']
        ];
        $groupPosts = $this->paginate($this->GroupPosts);

        $this->set(compact('groupPosts'));
    }

    /**
     * View method
     *
     * @param string|null $id Group Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $groupPost = $this->GroupPosts->get($id, [
            'contain' => ['Groups', 'Posts']
        ]);

        $this->set('groupPost', $groupPost);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $groupPost = $this->GroupPosts->newEntity();
        if ($this->request->is('post')) {
            $groupPost = $this->GroupPosts->patchEntity($groupPost, $this->request->getData());
            if ($this->GroupPosts->save($groupPost)) {
                $this->Flash->success(__('The group post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group post could not be saved. Please, try again.'));
        }
        $groups = $this->GroupPosts->Groups->find('list', ['limit' => 200]);
        $posts = $this->GroupPosts->Posts->find('list', ['limit' => 200]);
        $this->set(compact('groupPost', 'groups', 'posts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $groupPost = $this->GroupPosts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $groupPost = $this->GroupPosts->patchEntity($groupPost, $this->request->getData());
            if ($this->GroupPosts->save($groupPost)) {
                $this->Flash->success(__('The group post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group post could not be saved. Please, try again.'));
        }
        $groups = $this->GroupPosts->Groups->find('list', ['limit' => 200]);
        $posts = $this->GroupPosts->Posts->find('list', ['limit' => 200]);
        $this->set(compact('groupPost', 'groups', 'posts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $groupPost = $this->GroupPosts->get($id);
        if ($this->GroupPosts->delete($groupPost)) {
            $this->Flash->success(__('The group post has been deleted.'));
        } else {
            $this->Flash->error(__('The group post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
