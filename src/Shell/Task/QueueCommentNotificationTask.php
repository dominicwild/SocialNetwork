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
 * @property \App\Model\Table\PostsTable $Posts
 * @var Post $post
 */
class QueueCommentNotificationTask extends QueueTask {

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
        $this->loadModel('Queue.QueuedJobs');
        $job = $this->QueuedJobs->get($jobId);
        $sem = sem_get($job->reference);
        sem_acquire($sem);

        $micro = microtime(true);
        $job->fetched_micro = $micro;
//        debug("Saved: " . $micro);
        $this->QueuedJobs->save($job);

        $post_id = $data["post_id"];
        $time = $data["time"];
        $this->loadModel("Posts");
        $post = $this->Posts->get($post_id, ["contain" => [
            "Users",
            "Comments" => [
                "Users",
                "queryBuilder" => function ($q) use ($time) {
                    return $q->where(["created_time >=" => $time]);
                },
            ]
        ]
        ]);
        $this->loadModel("Users");

        $email = new Email();
        $email->setTransport("gmail");
        $email->viewBuilder()->setLayout("default");
        $email->viewBuilder()->setTemplate('new-comments');
        $email->viewBuilder()->setHelpers(["Miscellaneous"]);

        //Get users subscribed to emails on this post
        $user_ids = $data["user_ids"];
        foreach($user_ids as $user_id){
            $user_id = (int)$user_id;
        }

        $users = $this->Users->find()->where(["id IN" => $user_ids],['id' => 'integer[]'])->select(["email"]);
        $emails = [];
        debug("Gathering emails...");
        foreach($users as $user){
            $emails[] = $user->email;
            debug("Sending email to: " . $user->email);
        }

        //Identify unique user_ids
        $comment_user_emails = [];

        foreach($post->comments as $comment){
            if(!(in_array($comment->user->id, $comment_user_emails))){
                $comment_user_emails[] = $comment->user->email;
            }
        }

        //Remove single instance of user, so they don't get notified of their own singular comment
        if(count($comment_user_emails) == 1 && ($index = array_search($comment_user_emails[0], $emails)) !== false){
            unset($emails[$index]);
        }

        $email->setTo($emails)
            ->setSubject("Yordas Social - New Post Comments")
            ->setViewVars([
                "post" => $post,
                "email" => $email,
            ])
            ->setDomain("www.example.com")
            ->send();

        sem_release($sem);
        return true;
    }

}
