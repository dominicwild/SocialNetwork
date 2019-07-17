<?php
namespace App\Command;

use App\Model\Table\EventsTable;
use App\Model\Table\GroupsTable;
use App\Model\Table\PostImagesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use CakeDC\Users\Model\Table\UsersTable;

/**
 * CleanImages command.
 * @property PostImagesTable $PostImages
 * @property UsersTable $Users
 * @property EventsTable $Events
 * @property GroupsTable $Groups
 */
class CleanImagesCommand extends Command
{

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser) {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $path = "./../img/users/";
		$path = WWW_ROOT . "img/users/";
        $files = array_diff(scandir($path), array('.', '..'));
		
		debug($path);
		debug($files);
		debug(getcwd());
		
        $this->loadModel("PostImages");
        $this->loadModel("Groups");
        $this->loadModel("Events");
        $this->loadModel("Users");
        $images = $this->PostImages->find()->select("image")->toArray();
        $groups = $this->Groups->find()->select("image")->toArray();
        $events = $this->Events->find()->select("image")->toArray();
        $users = $this->Users->find()->select("profile_image")->toArray();

        $i = 0;

        $image_names = [];
        foreach($images as $image){
            $image_names[] = explode("/",$image->image)[3];
        }
        foreach($groups as $image){
            $image_names[] = explode("/",$image->image)[3];
        }
        foreach($events as $image){
            $image_names[] = explode("/",$image->image)[3];
        }
        foreach($users as $image){
            $image_names[] = explode("/",$image->profile_image)[3];
        }


        $toRemove = [];
        foreach($files as $file){
            if(!in_array($file, $image_names)){
                $toRemove[] = $file;
            }
        }

        foreach($toRemove as $file){
            unlink("./webroot/img/users/" . $file);
        }
        debug($toRemove);
        $io->out("Images removed " . count($toRemove));
//        debug(explode("/",$image_name));
    }
}
