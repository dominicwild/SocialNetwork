<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PollVotes Controller
 *
 * @property \App\Model\Table\PollVotesTable $PollVotes
 * @property \App\Model\Table\PollsTable $Polls
 *
 * @method \App\Model\Entity\PollVote[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PollVotesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Polls', 'Users', 'PollOptions']
        ];
        $pollVotes = $this->paginate($this->PollVotes);

        $this->set(compact('pollVotes'));
    }

    /**
     * View method
     *
     * @param string|null $id Poll Vote id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pollVote = $this->PollVotes->get($id, [
            'contain' => ['Polls', 'Users', 'PollOptions']
        ]);

        $this->set('pollVote', $pollVote);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->viewBuilder()->setLayout("ajax");
        if ($this->request->is('post')) {

            $id = (int)$_POST["id"];
            $user_id = (int)$this->Auth->user("id");
            unset($_POST["id"]); //unset to step through all option id votes
            $votes = [];

            $this->loadModel("Polls");
            $poll = $this->Polls->get($id);
            if($poll->expires > time() || $poll->expires == -1) {

                foreach (array_values($_POST) as $option_id) {
                    $vote = $this->PollVotes->newEntity();
                    $vote->poll_id = $id;
                    $vote->user_id = $user_id;
                    $vote->option_id = $option_id;
                    $votes[] = $vote;
                }

                if ($this->PollVotes->getConnection()->transactional($this->saveVotes($votes))) {
                    $this->loadModel("Polls");
                    $poll = $this->Polls->find("RenderContent", ["user" => $this->Auth->user("id")])->where(["id" => $id])->first();
                    $this->set("poll", $poll);
                    return $this->render("/Element/poll-results");
                }
            }
        }
        return $this->response->withStringBody("");
    }

    private function saveVotes($votes){
        return function() use ($votes){
            foreach($votes as $vote){
                if(!$this->PollVotes->save($vote)){
                    return false;
                }
            }
            return true;
        };
    }

    public function resetVote(){
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $poll_id = $_POST["id"];
            $this->PollVotes->deleteAll(["user_id" => $this->Auth->user("id"), "poll_id" => $poll_id]);

            $this->loadModel("Polls");
            $poll = $this->Polls->find("RenderContent", ["user"=>$this->Auth->user("id")])->where(["id" => $poll_id])->first();

            $this->set("poll", $poll);
            return $this->render("/Element/poll-vote");
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Poll Vote id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $pollVote = $this->PollVotes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pollVote = $this->PollVotes->patchEntity($pollVote, $this->request->getData());
            if ($this->PollVotes->save($pollVote)) {
                $this->Flash->success(__('The poll vote has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The poll vote could not be saved. Please, try again.'));
        }
        $polls = $this->PollVotes->Polls->find('list', ['limit' => 200]);
        $users = $this->PollVotes->Users->find('list', ['limit' => 200]);
        $pollOptions = $this->PollVotes->PollOptions->find('list', ['limit' => 200]);
        $this->set(compact('pollVote', 'polls', 'users', 'pollOptions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Poll Vote id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pollVote = $this->PollVotes->get($id);
        if ($this->PollVotes->delete($pollVote)) {
            $this->Flash->success(__('The poll vote has been deleted.'));
        } else {
            $this->Flash->error(__('The poll vote could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
