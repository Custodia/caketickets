<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketsUsersTable Test Case
 */
class TicketsUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketsUsersTable
     */
    public $TicketsUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tickets_users',
        'app.tickets',
        'app.projects',
        'app.users',
        'app.projects_users',
        'app.tickets_comments',
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
        $config = TableRegistry::exists('TicketsUsers') ? [] : ['className' => 'App\Model\Table\TicketsUsersTable'];
        $this->TicketsUsers = TableRegistry::get('TicketsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TicketsUsers);

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
