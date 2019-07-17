<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Email;

/**
 * Test command.
 * @property \App\Model\Table\EventsTable $Events
 * @property \App\Model\Table\EventParticipantsTable $EventParticipants
 * @property \App\Model\Table\UsersTable $Users
 */
class EventEmailCommand extends Command {

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
        $email = new Email();
        $email->setTransport("gmail");
        $email->viewBuilder()->setLayout("default");
        $email->viewBuilder()->setTemplate('event-email');
        $email->viewBuilder()->setHelpers(["Miscellaneous"]);

        $this->loadModel("Events");
        $this->loadModel("EventParticipants");
        $this->loadModel("Users");
        $events = $this->Events->find()
            ->where(["date >=" => time(), "date <=" => time() + 60*60*24*7])
            ->order(["date" => "ASC"])
            ->contain(['Posts', 'Groups'])->toArray();

        foreach($events as $event){
            $event->amount_at_event = count($this->EventParticipants->find()->where(["event_id" => $event->id])->toArray());
        }

        $users = $this->Users->find()->where(["weekly_event_email" => true])->select(["email"]);
        $emails = [];
        foreach($users as $user){
            $emails[] = $user->email;
        }

        $email->setTo($emails)
            ->setSubject("Yordas Social - Events of This Week")
            ->setViewVars([
                "events" => $events,
                "email_version" => 1,
                "email" => $email,
                "io" => $io,
            ])
            ->setDomain("www.example.com")
            ->send();

        $io->out('Event email sent.');
    }
}
