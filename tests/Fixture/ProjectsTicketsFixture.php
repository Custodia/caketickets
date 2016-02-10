<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProjectsTicketsFixture
 *
 */
class ProjectsTicketsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'project_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ticket_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['project_id', 'ticket_id'], 'length' => []],
            'ticket_id' => ['type' => 'unique', 'columns' => ['ticket_id'], 'length' => []],
            'project_ticket_key' => ['type' => 'foreign', 'columns' => ['project_id'], 'references' => ['projects', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'ticket_project_key' => ['type' => 'foreign', 'columns' => ['ticket_id'], 'references' => ['tickets', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'project_id' => 1,
            'ticket_id' => 1
        ],
    ];
}
