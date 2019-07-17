<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PollVotesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PollVotesTable Test Case
 */
class PollVotesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PollVotesTable
     */
    public $PollVotes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PollVotes',
        'app.Polls',
        'app.Users',
        'app.PollOptions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PollVotes') ? [] : ['className' => PollVotesTable::class];
        $this->PollVotes = TableRegistry::getTableLocator()->get('PollVotes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PollVotes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
