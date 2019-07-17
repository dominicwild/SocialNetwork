<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserPostNotificationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserPostNotificationsTable Test Case
 */
class UserPostNotificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserPostNotificationsTable
     */
    public $UserPostNotifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UserPostNotifications',
        'app.Users',
        'app.Posts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserPostNotifications') ? [] : ['className' => UserPostNotificationsTable::class];
        $this->UserPostNotifications = TableRegistry::getTableLocator()->get('UserPostNotifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserPostNotifications);

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
