<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PollOptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PollOptionsTable Test Case
 */
class PollOptionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PollOptionsTable
     */
    public $PollOptions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PollOptions',
        'app.Polls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PollOptions') ? [] : ['className' => PollOptionsTable::class];
        $this->PollOptions = TableRegistry::getTableLocator()->get('PollOptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PollOptions);

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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
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
