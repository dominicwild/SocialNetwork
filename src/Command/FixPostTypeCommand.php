<?php
namespace App\Command;

use App\Model\Entity\Post;
use App\Model\Table\EventsTable;
use App\Model\Table\GroupPostsTable;
use App\Model\Table\PostsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * FixPostType command.
 * @property PostsTable $Posts
 * @property EventsTable $Events
 * @property GroupPostsTable $GroupPosts
 */
class FixPostTypeCommand extends Command
{

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * This method applies the correct the post_type to all posts in the database. Used to initialize the field during development.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {

        $this->loadModel("Posts");
        $this->loadModel("GroupPosts");
        $this->loadModel("Events");

        $posts = $this->Posts->find("all");

        foreach($posts as $post){
            $id = $post->id;
            $event = $this->Events->find()->where(["post_id" => $id])->first();
            debug($event);
            debug((bool)$event);
            if((bool)$event){
                $post->post_type = Post::TYPE_EVENT;
            }

            $groupPost = $this->GroupPosts->find()->where(["post_id" => $id])->first();
            debug($groupPost);
            debug((bool)$groupPost);
            if((bool)$groupPost){
                $post->post_type = Post::TYPE_GROUP;
            }

            if(!($groupPost) && !($event)){
                $post->post_type = POST::TYPE_WALL;
            }

            $this->Posts->save($post);
        }


    }
}
