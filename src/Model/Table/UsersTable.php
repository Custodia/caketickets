<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Projects
 * @property \Cake\ORM\Association\HasMany $TicketsComments
 * @property \Cake\ORM\Association\BelongsToMany $Projects
 * @property \Cake\ORM\Association\BelongsToMany $Tickets
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('username');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Projects', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ProjectsUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('TicketsComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Projects', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'project_id',
            'joinTable' => 'projects_users'
        ]);
        $this->belongsToMany('Tickets', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'ticket_id',
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
            ->requirePresence('username', 'create')
            ->notEmpty('username', 'A username is required.')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'A password is required.');

        $validator
            ->notEmpty('role', 'A role is required.')
            ->add('role', 'inList', [
                'rule' => ['inList',['Admin', 'User']],
                'message' => 'Please enter a valid role.'
            ]);

        return $validator;
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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
