<?php
namespace App\Model\Table;

use App\Model\Entity\Ticket;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tickets Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Projects
 * @property \Cake\ORM\Association\BelongsToMany $Comments
 * @property \Cake\ORM\Association\BelongsToMany $Users
 */
class TicketsTable extends Table
{

    public function findByTitle(Query $query, array $options)
    {
        $queries = $options['queries'];

        foreach ($queries as $index){
            $index = '%' . $index . '%';
            $query = $query->where(['title LIKE' => $index]);
        }

        return $query;
    }

    public function findByStatus(Query $query, array $options) 
    {
        $status = $options['status'];
        if (ucfirst($status) === 'All'){
            return $query;
        } else {
            return $query->where(['status' => ucfirst($status)]);
        }
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('tickets');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Projects', [
            'foreignKey' => 'ticket_id',
            'targetForeignKey' => 'project_id',
            'joinTable' => 'projects_tickets'
        ]);
        $this->belongsToMany('Comments', [
            'foreignKey' => 'ticket_id',
            'targetForeignKey' => 'comment_id',
            'joinTable' => 'tickets_comments'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'ticket_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'tickets_users'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('title');

        $validator
            ->allowEmpty('status');

        $validator
            ->allowEmpty('body');

        return $validator;
    }
}
