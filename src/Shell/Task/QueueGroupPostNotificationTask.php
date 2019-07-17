<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use App\Model\Table\GroupMembersTable;
use App\Model\Table\GroupsTable;
use Cake\Mailer\Email;
use Queue\Shell\Task\QueueTask;

//use Tools\Mailer\Email;
/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PostsTable $Posts
 * @property GroupMembersTable $GroupMembers
 * @property GroupsTable $Groups
 * @var Post $post
 */
class QueueGroupPostNotificationTask extends QueueTask {

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
        $email->viewBuilder()->setTemplate('new-group-post');
        $email->viewBuilder()->setHelpers(["Miscellaneous"]);

        $post_id = $data["post_id"];
        $group_id = $data["group_id"];

        $this->loadModel("Posts");
        $post = $this->Posts->get($post_id, ["contain" => ["PostImages", "Users",]]);

        $this->loadModel("Groups");
        $group = $this->Groups->get($group_id);

        $this->loadModel("GroupMembers");
        $group_members = $this->GroupMembers->find()->contain(["Users"])->where(["group_id" => $group_id]);
        $emails = [];
        foreach($group_members as $member){
            $emails[] = $member->user->Email;
        }

        //$emails = ["d.wild@yordasgroup.com"];

        $email->setTo($emails)
            ->setSubject("Yordas Social - New Post in Group: " . $group->name)
            ->setViewVars([
                "post" => $post,
                "email" => $email,
            ])
            ->setDomain("www.example.com")
            ->send();

        return true;
    }

}
