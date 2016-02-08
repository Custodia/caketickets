<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProjectsTicketsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProjectsTicketsTable Test Case
 */
class ProjectsTicketsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProjectsTicketsTable
     */
    public $ProjectsTickets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.projects_tickets',
        'app.projects',
        'app.users',
        'app.projects_users',
        'app.tickets_comments',
        'app.tickets',
        'app.comments',
        'app.tickets_users',
        'app.tags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProjectsTickets') ? [] : ['className' => 'App\Model\Table\ProjectsTicketsTable'];
        $this->ProjectsTickets = TableRegistry::get('ProjectsTickets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProjectsTickets);

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
