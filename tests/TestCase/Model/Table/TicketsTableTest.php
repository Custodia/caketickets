<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketsTable Test Case
 */
class TicketsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketsTable
     */
    public $Tickets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tickets',
        'app.projects',
        'app.users',
        'app.projects_users',
        'app.tickets_comments',
        'app.tickets_users',
        'app.tags',
        'app.projects_tickets',
        'app.comments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Tickets') ? [] : ['className' => 'App\Model\Table\TicketsTable'];
        $this->Tickets = TableRegistry::get('Tickets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tickets);

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
