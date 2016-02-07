<?php
namespace App\Model\Table;

use App\Model\Entity\ProjectsUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProjectsUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Projects
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class ProjectsUsersTable extends Table
{

    /*
     * Is the given user a moderator for the
     * given project.
     */
    public function isModeratedBy($projectId, $userId)
    {
        return $this->exists([
            'project_id' => $projectId, 
            'user_id' => $userId, 
            'role' => 'Admin']);
    }

    /*
     * Is the given user assigned to the
     * given project.
     */
    public function isAssignedTo($projectId, $userId)
    {
        return $this->exists([
            'project_id' => $projectId, 
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

        $this->table('projects_users');
        $this->displayField('project_id');
        $this->primaryKey(['project_id', 'user_id']);

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('role');

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
        $rules->add($rules->existsIn(['project_id'], 'Projects'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
