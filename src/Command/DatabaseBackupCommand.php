<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * DatabaseBackup command.
 */
class DatabaseBackupCommand extends Command
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
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $mySqlPath = "./../../mysql/bin/";
        $backupPath = "./DatabaseBackups/";
        if(!file_exists($backupPath)){
            mkdir($backupPath);
        }

        $time = time();
        $files = array_diff(scandir($backupPath), array('.', '..'));

        if(sizeof($files) > 10){
            debug($files);
            $lowest = INF;
            foreach($files as $file){
                $file = explode(".",$file)[0];
                debug($file);
                if($lowest > +$file){
                    $lowest = +$file;
                }
            }
            unlink($backupPath . $lowest . ".sql");
        }

        $source = ConnectionManager::get('default');

        $config = $source->config();

        $commandPath = $mySqlPath;

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo 'This is a server using Windows!';
            $commandPath = str_replace("/","\\",$mySqlPath);
        }

        debug($commandPath);
        exec($commandPath . "mysqldump --host=localhost --user=" . $config["username"] . " --password=" . $config["password"] .  " " . $config["database"] . " > " . $backupPath . $time . ".sql");

        $io->out($time);
    }
}
