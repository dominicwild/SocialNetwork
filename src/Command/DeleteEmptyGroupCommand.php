<?php
namespace App\Command;

use App\Model\Table\GroupsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * DeleteEmptyGroup command.
 * @property GroupsTable $Groups
 */
class DeleteEmptyGroupCommand extends Command
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
        $this->loadModel("Groups");
        $groups = $this->Groups->find()->contain(["GroupMembers"]);
        foreach($groups as $group){
            if($group->group_members == []){
               $this->Groups->delete($group);
               $io->out("Group deleted due to being empty: " . $group->name);
            }
        }
        $io->out("Finished delete groups task.");
    }
}
