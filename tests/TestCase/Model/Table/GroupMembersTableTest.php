<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupMembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupMembersTable Test Case
 */
class GroupMembersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupMembersTable
     */
    public $GroupMembers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GroupMembers',
        'app.Users',
        'app.Groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupMembers') ? [] : ['className' => GroupMembersTable::class];
        $this->GroupMembers = TableRegistry::getTableLocator()->get('GroupMembers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupMembers);

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
