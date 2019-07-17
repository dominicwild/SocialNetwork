<?php
namespace App\Shell\Task;

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
 */
class QueueCommentProcessTask extends QueueTask {

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

        $this->loadModel("UserPostNotifications");
        $this->loadModel('Queue.QueuedJobs');
        $post_id = $data["post_id"];
        $time = $data["time"];

        $notifs = $this->UserPostNotifications->find()->where(["post_id" => $post_id])->contain(["Users"])->toArray();

        $instant = [];
        $ten = [];
        $day = []; //12 hours

        if(count($notifs) > 0) {
            foreach ($notifs as $notif) {
                $user = $notif->user;
                switch ($user->comment_notification_option) {
                    case 0:
                        $instant[] = $user->id;
                        break;
                    case 1:
                        $ten[] = $user->id;
                        break;
                    case 2:
                        $day[] = $user->id;
                        break;
                }
            }

            $this->queueCommentNotif($instant, round($time), 0, $post_id, $time);
            $this->queueCommentNotif($ten, round($time) + 10 * 60, 1, $post_id, $time);
            $this->queueCommentNotif($day, round($time) + 60 * 60 * 12, 2, $post_id, $time);
        }
        return true;
    }

    private function queueCommentNotif($user_ids, $before,$option, $post_id, $time){
        $this->loadModel('Queue.QueuedJobs');
        if(count($user_ids) > 0) { //If there is someone to notify
            $reference = $option . "o-" . $post_id;
            $sem = sem_get($reference);
            sem_acquire($sem);
            if (!($this->isQueued($reference, "CommentNotification", $time))) { //If task not already queued and there are existing user_ids
                $this->QueuedJobs->createJob('CommentNotification',
                    ["post_id" => $post_id,
                        "time" => $time,
                        "user_ids" => $user_ids
                    ],
                    [
                        "notBefore" => $before,
                        "reference" => $reference
                    ]);
            }
            sem_release($sem);
        }
    }

    //Modified isQueued function from QueuedTasks plugin
    private function isQueued($reference, $jobType = null, $time) {
        if (!$reference) {
            throw new InvalidArgumentException('A reference is needed');
        }

        $conditions = [
            'reference' => $reference,
            'completed IS' => null,
            "failed" => 0,
        ];
        if ($jobType) {
            $conditions['job_type'] = $jobType;
        }

        $job = $this->QueuedJobs->find()->where($conditions)->first();

        $conditions = [
            'reference' => $reference,
            "failed" => 0,
        ];
        $order = [
            "fetched_micro" => "DESC"
        ];

        $max_fetched = $this->QueuedJobs->find()->where($conditions)->order($order)->first();
        $max_fetched = $max_fetched->fetched_micro;

        if($time <= $max_fetched){ //No need to renotify of older comments
            return true;
        }

        if ($job) {
            $job_time = strtotime($job->created);
            if ($time < $job_time) { //If our time is earlier
                if ($this->QueuedJobs->delete($job)) {
                    return false; //Say true as we will replace this job
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
