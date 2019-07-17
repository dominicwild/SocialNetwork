<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\PollsTable;
use App\Model\Table\PollUserOptionsTable;
use App\Model\Table\PollVotesTable;

/**
 * PollOptions Controller
 *
 * @property \App\Model\Table\PollOptionsTable $PollOptions
 * @property PollsTable $Polls
 * @property PollVotesTable $PollVotes
 * @property PollUserOptionsTable $PollUserOptions
 *
 * @method \App\Model\Entity\PollOption[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PollOptionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Polls']
        ];
        $pollOptions = $this->paginate($this->PollOptions);

        $this->set(compact('pollOptions'));
    }

    /**
     * View method
     *
     * @param string|null $id Poll Option id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pollOption = $this->PollOptions->get($id, [
            'contain' => ['Polls']
        ]);

        $this->set('pollOption', $pollOption);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->viewBuilder()->setLayout("ajax");
        if ($this->request->is('post')) {
            $poll_option = $this->PollOptions->newEntity(["associated" => "PollUserOptions"]);

            $poll_id = $_POST["poll_id"];
            $option_name = $_POST["option_name"];

            $poll_option->poll_id = $poll_id;
            $poll_option->option_name = $option_name;

            $this->loadModel("Polls");
            $poll = $this->Polls->get($poll_id);

            $this->loadModel("PollUserOptions");
            $user_option = $this->PollUserOptions->newEntity();
            $user_option->user_id = $this->Auth->user("id");
            $poll_option->poll_user_option = $user_option;

            if ($poll->user_add_options && $this->PollOptions->save($poll_option)) {
                $poll_option->is_user_option = true;
                $poll->poll_options = [$poll_option];
                $this->set("poll", $poll);

                $this->set("no_hidden",true);
                return $this->render("/Element/poll-vote");
            }

        }
        return $this->response->withStringBody("");
    }

    /**
     * Edit method
     *
     * @param string|null $id Poll Option id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pollOption = $this->PollOptions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pollOption = $this->PollOptions->patchEntity($pollOption, $this->request->getData());
            if ($this->PollOptions->save($pollOption)) {
                $this->Flash->success(__('The poll option has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The poll option could not be saved. Please, try again.'));
        }
        $polls = $this->PollOptions->Polls->find('list', ['limit' => 200]);
        $this->set(compact('pollOption', 'polls'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Poll Option id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $poll_option = $this->PollOptions->get($_POST["id"], ["contain" => "PollVotes"]);
            if(count($poll_option->poll_votes) == 0){
                if($this->PollOptions->delete($poll_option)){
                    return $this->response->withStringBody("true");
                }
            }
        }
        return $this->response->withStringBody("");
    }
}
