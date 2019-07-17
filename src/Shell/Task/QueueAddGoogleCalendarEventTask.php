<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventOrganizer;
use Google_Service_Calendar_EventReminders;
use Queue\Shell\Task\QueueTask;

//use Tools\Mailer\Email;
/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PostsTable $Posts
 * @var Post $post
 */
class QueueAddGoogleCalendarEventTask extends GoogleTask {

    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return bool Success
     */
    public function run(array $data, $jobId) {

        $event_id = $data["event_id"];
        $email = $data["email"];
        $calendar_id = Configure::read("GoogleSharedCalender.calendar_id");
        $edit = isset($data["edit"]) ? $data["edit"] : false;

        $this->loadModel("Events");
        $this->loadModel("Users");
        $event = $this->Events->get($event_id, ["contain" => ["Posts"]]);

        $gClient = $this->getServiceClient();

        $calendar = new Google_Service_Calendar($gClient);

        $end_date = new Google_Service_Calendar_EventDateTime();
        if ($event->end_date != null || $event->end_date != 0) {
            $end_date->setDateTime("" . date("c",$event->end_date));
        } else {
            $end_date->setDateTime("" . date("c",$event->date + 60 * 60 * 24));
        }

        $start_date = new Google_Service_Calendar_EventDateTime();
        $start_date->setDateTime("" . date("c", $event->date));

        if($edit) {
            $calendar_event = $calendar->events->get($calendar_id, $event->calendar_event_id);
        } else{
            $calendar_event = new Google_Service_Calendar_Event();
        }

        $organiser = new Google_Service_Calendar_EventOrganizer();
        $organiser->setEmail($email);

        $calendar_event->setSummary($event->title);
        $calendar_event->setLocation($event->place);
        $calendar_event->setDescription($event->post->content);
        $calendar_event->setStart($start_date);
        $calendar_event->setEnd($end_date);
        $calendar_event->setId($event->calendar_event_id);
        $calendar_event->setOrganizer($organiser);
        //$calendar_event->setReminders($event->title);

//        $calendar_event = new Google_Service_Calendar_Event(array(
//            'summary' => $event->title,
//            'location' => $event->place,
//            'description' => $event->post->content,
//            'start' => array(
//                'dateTime' => "" . date("c", $start_date),
//                'timeZone' => 'Europe/London',
//            ),
//            'end' => array(
//                'dateTime' => "" . date("c", $end_date),
//                'timeZone' => 'Europe/London',
//            ),
//            'reminders' => array(
//                'useDefault' => FALSE,
//                'overrides' => array(
//                    array('method' => 'email', 'minutes' => 24 * 60),
//                    array('method' => 'popup', 'minutes' => 10),
//                ),
//            ),
//            'id' => $event->calendar_event_id,
//        ));

        if($edit){
            $calendar_event = $calendar->events->patch($calendar_id,$event->calendar_event_id, $calendar_event, ["sendUpdates" => "all"]);
        } else {
            $calendar_event = $calendar->events->insert($calendar_id, $calendar_event);
        }
        debug('Event created: %s\n ' . $calendar_event->htmlLink);

        file_put_contents("event.txt","The Event link is: " . $calendar_event->htmlLink);

        return true;
    }

    private function runPersonal($data){
        $event_id = $data["event_id"];
        $user_id = $data["user_id"];
        $calendar_event_id = $data["calendar_event_id"];

        $this->loadModel("Events");
        $this->loadModel("Users");
        $event = $this->Events->get($event_id, ["contain" => "Posts"]);
        $user = $this->Users->get($user_id);

        $access_token = $user->access_token;
        $gClient = $this->getClient($access_token);
        $calendar = new Google_Service_Calendar($gClient);

        $start_date = $event->date;
        if ($event->end_date != null || $event->end_date != 0) {
            $end_date = $event->end_date;
        } else {
            $end_date = $start_date + 60 * 60 * 24;
        }

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $event->title,
            'location' => $event->place,
            'description' => $event->post->content,
            'start' => array(
                'dateTime' => "" . date("c", $start_date),
                'timeZone' => 'Europe/London',
            ),
            'end' => array(
                'dateTime' => "" . date("c", $end_date),
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

        $calendarId = 'primary';
        $event = $calendar->events->insert($calendarId, $event);
        debug('Event created: %s\n ' . $event->htmlLink);

        return true;
    }

}
