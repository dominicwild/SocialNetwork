<?php
namespace App\Shell\Task;

use App\Model\Table\ReportedPostsTable;
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
 * @property ReportedPostsTable $ReportedPosts
 * @property \App\Model\Table\UserPostNotificationsTable $UserPostNotifications
 * @property \App\Model\Table\QueuedJobsTable $QueuedJobs
 */
class QueueReportedPostEmailTask extends QueueTask {

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
        $id = $data["report_id"];

        $email = new Email();
        $email->setTransport("gmail");
        $email->viewBuilder()->setLayout("default");
        $email->viewBuilder()->setTemplate('new-reported-post');

        $this->loadModel("ReportedPosts");
        $report = $this->ReportedPosts->get($id,["contain" => ["Users"]]);

        $emails = [];
        $this->loadModel("Users");
        $users = $this->Users->find()->where(["Permissions" => 100]);
        foreach($users as $user){
            $emails[] = $user->Email;
        }

        $email->setTo($emails)
            ->setSubject("Yordas Social - New Reported Post")
            ->setViewVars([
                "report" => $report,
            ])
            ->send();

        return true;
    }

}
