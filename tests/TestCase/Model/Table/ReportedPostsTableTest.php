<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportedPostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportedPostsTable Test Case
 */
class ReportedPostsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportedPostsTable
     */
    public $ReportedPosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReportedPosts',
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
        $config = TableRegistry::getTableLocator()->exists('ReportedPosts') ? [] : ['className' => ReportedPostsTable::class];
        $this->ReportedPosts = TableRegistry::getTableLocator()->get('ReportedPosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportedPosts);

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
