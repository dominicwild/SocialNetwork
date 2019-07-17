<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PollUserOptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PollUserOptionsTable Test Case
 */
class PollUserOptionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PollUserOptionsTable
     */
    public $PollUserOptions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PollUserOptions',
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
        $config = TableRegistry::getTableLocator()->exists('PollUserOptions') ? [] : ['className' => PollUserOptionsTable::class];
        $this->PollUserOptions = TableRegistry::getTableLocator()->get('PollUserOptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PollUserOptions);

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
