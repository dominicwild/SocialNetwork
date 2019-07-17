<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventAttendee;
use Google_Service_Calendar_EventDateTime;
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
class QueueUpdateGoogleCalendarAttendeeTask extends GoogleTask {

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
        $email = $data["email"];        //Email to add or remove
        $calendar_id = Configure::read("GoogleSharedCalender.calendar_id");
        $event_calendar_id = $this->convertToCalendarEventId($event_id);
        $toAdd = $data["toAdd"];        //True to add, false is to remove

        $this->loadModel("Events");

        $gClient = $this->getServiceClient();
        $calendar = new Google_Service_Calendar($gClient);
        $calendar_event = $calendar->events->get($calendar_id, $event_calendar_id);

        $attendees = $calendar_event->getAttendees();

        debug($attendees);

        $found = false;
        $foundIndex = -1;
        foreach($attendees as $attendee){
            $foundIndex++;
            if($attendee->email === $email){
                $found = true;
                break;
            }
        }

        if($found == $toAdd){
            return true;
        }

        $event = $this->Events->get($event_id, ["contain" => ["EventParticipants" => ["Users"]]]);

        $updatedAttendees = [];
        foreach($event->event_participants as $participant){
            $attendee = new Google_Service_Calendar_EventAttendee();
            $attendee->setEmail($participant->user->Email);
            $attendee->setResponseStatus("accepted");
            $updatedAttendees[] = $attendee;
        }

        $calendar_event->setAttendees($updatedAttendees);

        $calendar_event = $calendar->events->patch($calendar_id,$event_calendar_id, $calendar_event, ["sendUpdates" => "all"]);

//        $calendar_event = $calendar->events->patch($calendar_id,$event->calendar_event_id, $calendar_event, ["sendUpdates" => "all"]);
//
//        debug('Event created: %s\n ' . $calendar_event->htmlLink);
//
//        file_put_contents("event.txt","The Event link is: " . $calendar_event->htmlLink);

        return true;
    }

}
