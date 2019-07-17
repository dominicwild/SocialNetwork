<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Misc Controller
 *
 *
 * @method \App\Model\Entity\Misc[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MiscController extends AppController {


//    public function initialize() {
//        parent::initialize();
//        $this->Auth->allow(['updatePull']);
//    }
//
//    public function updatePull(){
//        $log = $_SERVER['DOCUMENT_ROOT'] . "/pull.txt";
//        $file = fopen($log,"a");
//
//        $post_body = file_get_contents("php://input");
//        $secret = "LAgutipYai2piFwljfJiCqUesPqZndx7lLJ3Inz4BfgLlCgdZS1uqNduqUbH";
//        if('sha1=' . hash_hmac( 'sha1', $post_body, $secret, false ) === $_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]){// ideally you want to use a method like secure_compare, not ===
//            `git pull`;
//            $payload = json_decode($post_body,true);
//            $record = "Pulled from: " . $payload["pusher"]["name"] . PHP_EOL .
//                "On date: " . date("r") . PHP_EOL .
//                "Message: " . $payload["head_commit"]["message"] . PHP_EOL;
//            fwrite($file,$record);
//        }
//        echo shell_exec("git pull 2>&1");
//        echo "<br>" . shell_exec("whoami");
//        echo "<br>" . "A change has been madeeeeee";
//        return $this->response->withStatus("500");
//    }

}
