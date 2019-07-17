<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Google_Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Exception;
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
class QueueRemoveGoogleCalendarEventTask extends GoogleTask {

    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 1;

    public $attemptNum = 10;

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return bool Success
     */
    public function run(array $data, $jobId) {

            $calendar_event_id = $data["calendar_event_id"];

            $this->loadModel("Users");

            $gClient = $this->getServiceClient();

            $calendar = new Google_Service_Calendar($gClient);
            $calendar_id = Configure::read("GoogleSharedCalender.calendar_id");
            $calendar->events->delete($calendar_id, "" . $calendar_event_id);

            debug("deleted");
//        } catch(\Exception $e){
//            $this->loadModel('Queue.QueuedJobs');
//            if(isset($data["attempts"])){
//                $data["attempts"] += 1;
//            } else {
//                $data["attempts"] = 1;
//            }
//            debug("Service exception");
//            if($data["attempts"] < $this->attemptNum) {
//                $this->QueuedJobs->createJob('RemoveGoogleCalendarEvent', $data, ["notBefore" => time()+60*5]);
//            }
//            return true;
//        }

        return true;
    }

}
