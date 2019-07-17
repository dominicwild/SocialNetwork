<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AmbassadorsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AmbassadorsTable Test Case
 */
class AmbassadorsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AmbassadorsTable
     */
    public $Ambassadors;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Ambassadors'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Ambassadors') ? [] : ['className' => AmbassadorsTable::class];
        $this->Ambassadors = TableRegistry::getTableLocator()->get('Ambassadors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Ambassadors);

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
}
