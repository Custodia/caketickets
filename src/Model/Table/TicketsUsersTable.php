<?php
namespace App\Model\Table;

use App\Model\Entity\TicketsUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TicketsUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Tickets
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class TicketsUsersTable extends Table
{

    public function isAssignedTo($ticketId, $userId)
    {
        return $this->exists([
            'ticket_id' => $ticketId, 
            'user_id' => $userId]);
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

        $this->table('tickets_users');
        $this->displayField('ticket_id');
        $this->primaryKey(['ticket_id', 'user_id']);

        $this->belongsTo('Tickets', [
            'foreignKey' => 'ticket_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['ticket_id'], 'Tickets'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
