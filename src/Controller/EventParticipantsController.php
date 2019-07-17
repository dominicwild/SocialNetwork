<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\EventsTable;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Exception;

/**
 * EventParticipants Controller
 *
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property EventsTable Events
 *
 * @method \App\Model\Entity\EventParticipant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventParticipantsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Events']
        ];
        $eventParticipants = $this->paginate($this->EventParticipants);

        $this->set(compact('eventParticipants'));
    }

    /**
     * View method
     *
     * @param string|null $id Event Participant id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $eventParticipant = $this->EventParticipants->get($id, [
            'contain' => ['Users', 'Events']
        ]);

        $this->set('eventParticipant', $eventParticipant);
    }

    /**
     * Add method
     *
     */
    public function add() {
        $eventParticipant = $this->EventParticipants->newEntity();
        if ($this->request->is('post')) {
            $eventParticipant = $this->EventParticipants->patchEntity($eventParticipant, $this->request->getData());
            $eventParticipant->user_id = $this->Auth->user("id");
            $eventParticipant->calendar_event_id = $this->calenderEventId($eventParticipant->event_id);
            if ($this->EventParticipants->save($eventParticipant)) {
                //$this->addEventToGoogleCalendar($_POST["event_id"], $eventParticipant->calendar_event_id);
//                debug($eventParticipant->calendar_event_id);
                $this->loadModel('Queue.QueuedJobs');
                $data = [
                    "email" => $this->Auth->user("Email"),
                    "event_id" => $_POST["event_id"],
                    "toAdd" => true,
                ];
                $this->QueuedJobs->createJob('UpdateGoogleCalendarAttendee',$data);
                return $this->response->withStringBody("true");
            }
        }
        return $this->response->withStringBody("false");
    }

    public function removeEventFromGoogleCalendar($calendar_event_id){
        $this->viewBuilder()->setLayout("ajax");

        if($this->request->is("post")){

            $gClient = $this->getClient();
            $calendar = new Google_Service_Calendar($gClient);
            $calendar->events->delete('primary', "" . $calendar_event_id);

//            debug("deleted");

            //return $this->response->withStringBody("deleted");
        }
        //return $this->response->withStringBody("aaa");
    }

    public function addEventToGoogleCalendar($event_id, $calendar_event_id) {
        $this->viewBuilder()->setLayout("ajax");

        if($this->request->is("post")){

            $this->loadModel("Events");
            $event = $this->Events->get($event_id,["contain" => "Posts"]);

            $gClient = $this->getClient();
            $calendar = new Google_Service_Calendar($gClient);
//            $calendar->calendarList;
            $output = "";
//            debug($calendar->calendarList->listCalendarList());

            $start_date = $event->date;
            if($event->end_date != null || $event->end_date != 0) {
                $end_date = $event->end_date;
            } else {
                $end_date = $start_date + 60*60*24;
            }

            $event = new Google_Service_Calendar_Event(array(
                'summary' => $event->title,
                'location' => $event->place,
                'description' => $event->post->content,
                'start' => array(
                    'dateTime' => "" . date("c",$start_date),
                    'timeZone' => 'Europe/London',
                ),
                'end' => array(
                    'dateTime' => "" . date("c",$end_date),
                    'timeZone' => 'Europe/London',
                ),
                'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 10),
                    ),
                ),
                'id' => $calendar_event_id,
            ));

//            debug(date("c",$start_date));
//            debug(date("c",$end_date));
            $calendarId = 'primary';
            $event = $calendar->events->insert($calendarId, $event);
//            debug('Event created: %s\n ' . $event->htmlLink);

            //return $this->response->withStringBody($output);
        }
        //return $this->response->withStringBody("aaa");
    }

    private function calenderEventId($event_id){
        $id = "" . $event_id;
        $iterations = 1024 - strlen($id); //Google event id can have up to 1024 characters
        $chars = [];

        for($i=0;$i<10;$i++){
            $chars[] = chr(48 + $i);
        }

        for($i=0;$i<22;$i++){
            $chars[] = chr(97 + $i);
        }

        for($i=0;$i<$iterations;$i++){
            $rand = rand(0,sizeof($chars)-1);
            $id = $id . $chars[$rand];
        }

        return $id;
    }

    /**
     * Edit method
     *
     * @param string|null $id Event Participant id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $eventParticipant = $this->EventParticipants->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $eventParticipant = $this->EventParticipants->patchEntity($eventParticipant, $this->request->getData());
            if ($this->EventParticipants->save($eventParticipant)) {
                $this->Flash->success(__('The event participant has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The event participant could not be saved. Please, try again.'));
        }
        $users = $this->EventParticipants->Users->find('list', ['limit' => 200]);
        $events = $this->EventParticipants->Events->find('list', ['limit' => 200]);
        $this->set(compact('eventParticipant', 'users', 'events'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Event Participant id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $this->viewBuilder()->setLayout("ajax");
        if($this->request->is("post")){
            $participant = $this->EventParticipants->find()->where(["event_id" => $_POST["event_id"], "user_id" => $this->Auth->user("id")])->first();
            if($participant != null){

                if($this->EventParticipants->delete($participant)){
                    $this->loadModel('Queue.QueuedJobs');
                    $data = [
                        "email" => $this->Auth->user("Email"),
                        "event_id" => $_POST["event_id"],
                        "toAdd" => false,
                    ];
                    $this->QueuedJobs->createJob('UpdateGoogleCalendarAttendee',$data);
                    return $this->response->withStringBody("true");
                }
            }
            return $this->response->withStringBody("false");
        }
    }
}
