<?php
namespace App\Shell\Task;

use App\Model\Entity\Post;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Google_Client;
use Google_Service_Calendar;
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
class GoogleTask extends QueueTask {

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return bool Success
     */
    public function run(array $data, $jobId) {

        return true;
    }

    protected function getClient($access_token){

        $gClient = $this->googleConfig();
        $gClient->setAccessToken(json_decode($access_token, true));
        if ($gClient->isAccessTokenExpired()) { //Update if access token has expired
            $gClient->fetchAccessTokenWithRefreshToken();
        }
        return $gClient;
    }

    public function getServiceClient(){
        $gClient = new Google_Client();
        $fileName = Configure::read("GoogleServiceAccount.credentials_file");
		debug(ROOT . DS . $fileName);
        $gClient->setAuthConfig(ROOT . DS . $fileName);
        $gClient->setApplicationName("Yordas Social");
        $gClient->addScope(Google_Service_Calendar::CALENDAR);
        $gClient->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        return $gClient;
    }

    protected function convertToCalendarEventId($id){
        return "00000" . $id;
    }

    protected function googleConfig(){
        $gClient = new \Google_Client();
        $gClient->setClientId(Configure::read("GoogleApplication.client_id"));
        $gClient->setClientSecret(Configure::read("GoogleApplication.client_secret"));
        $gClient->setApplicationName("Login Test");
        $gClient->setRedirectUri("http://localhost:8765/users/login");
        $gClient->addScope("profile openid email https://www.googleapis.com/auth/calendar"); //https://www.googleapis.com/auth/calendar calender scope
        $gClient->setAccessType("offline");
        return $gClient;
    }

}
