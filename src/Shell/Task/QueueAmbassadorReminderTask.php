<?php
namespace App\Shell\Task;

use App\Model\Entity\Ambassador;
use App\Model\Table\AmbassadorsTable;
use Cake\I18n\FrozenTime;
use Cake\Mailer\Email;
use Queue\Model\Entity\QueuedJob;
use Queue\Shell\Task\QueueTask;

//use Tools\Mailer\Email;
/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PostsTable $Posts
 * @property \App\Model\Table\UserPostNotificationsTable $UserPostNotifications
 * @property \App\Model\Table\QueuedJobsTable $QueuedJobs
 * @property AmbassadorsTable $Ambassadors
 */
class QueueAmbassadorReminderTask extends QueueTask {

    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 2;

    public $interval = 60*60*24*7; //Seconds between checks of activity for email reminders

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return bool Success
     */
    public function run(array $data, $jobId) {

       $id = $data["user_id"];
       //$remind_time = $data["remind_time"];

       $this->loadModel("Ambassadors");
       //$this->loadModel("Posts");

//       $post = $this->Posts->find()->where(["id" => ">1"])->first();
//       debug($post);

       $ambassador = $this->Ambassadors->find()->where(["user_id" => $id])->first();

       if($ambassador) { //If they're still an ambassador
           $time = time();
           if ($time >= $ambassador->remind_time) { //If its running actually after the remind time
               if(!$this->beenActiveSince($id,$time)){
                   $this->loadModel("Users");
                   $user = $this->Users->get($id);
                   $this->sendEmail($user);
               }
               $this->loadModel('Queue.QueuedJobs');
               $ambassador->remind_time = $time + $this->interval; //Check reminder next week
               $this->Ambassadors->save($ambassador);
               $data = [
                   "user_id" => $ambassador->user_id,
               ];
               $this->QueuedJobs->createJob('AmbassadorReminder',$data,["notBefore" => $ambassador->remind_time]);
               debug("Reminder re-scheduled");
               return true;
           } else {
               debug("Ambassador remind time later than current time.");
               return true;
           }
       }
       debug("Ambassador is null.");
       return true;
    }

    private function beenActiveSince($id, $time){
        $this->loadModel("Posts");
        $post = $this->Posts->find()->where(["user_id" => $id])->order(["created_time" => "DESC"])->first();
        if($post){
            return $post->created_time >= ($time - $this->interval);
        } else {
            return false;
        }
    }

    private function sendEmail($user){
        $email = new Email();
        $email->setTransport("gmail");
        $email->viewBuilder()->setLayout("default");
        $email->viewBuilder()->setTemplate("ambassador-reminder");
        $email->viewBuilder()->setHelpers(["Miscellaneous"]);
        $email->setTo($user->Email)
            ->setSubject("Yordas Social - Ambassador Activity Reminder")
            ->setViewVars([
                "user" => $user,
            ])
            ->send();
        debug("Reminder email sent!");
        return true;
    }

}
