<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use Cake\Mailer\Email;
use Queue\Shell\Task\QueueTask;

//use Tools\Mailer\Email;
/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 */
class QueueEventNotificationTask extends QueueTask {

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
        $email = new Email();
        $email->setTransport("gmail");
        $email->viewBuilder()->setLayout("default");
        $email->viewBuilder()->setTemplate('new-event');
        $email->viewBuilder()->setHelpers(["Miscellaneous"]);

        $this->loadModel("Events");
        $this->loadModel("EventParticipants");
        $this->loadModel("Users");

        $event = $this->Events->get($data["id"], ["contain" => ["Groups" => ["GroupMembers" => ["Users"]],"Posts" =>["Users"]]]);
        $event->amount_at_event = count($this->EventParticipants->find()->where(["event_id" => $event->id])->toArray());

        $users = $this->Users->find()->where(["email_per_event" => true])->select(["email"]);
        $emails = [];
        foreach($users as $user){
            $emails[] = $user->email;
        }

        $members = $event->group->group_members;

        foreach($members as $member){
            $emails[] = $member->user->Email;
        }
		
        $email->setTo($emails)
            ->setSubject("Yordas Social - New Event: " . $event->title . " in Group " . $event->group->name)
            ->setViewVars([
                "event" => $event,
                "email_version" => 1,
                "email" => $email,
            ])
            ->setDomain("www.example.com")
            ->send();
			
		debug("Send emails to: " . $emails);
		
        return true;
    }

}
