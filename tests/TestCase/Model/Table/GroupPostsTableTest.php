<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupPostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupPostsTable Test Case
 */
class GroupPostsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupPostsTable
     */
    public $GroupPosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GroupPosts',
        'app.Groups',
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
        $config = TableRegistry::getTableLocator()->exists('GroupPosts') ? [] : ['className' => GroupPostsTable::class];
        $this->GroupPosts = TableRegistry::getTableLocator()->get('GroupPosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupPosts);

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
