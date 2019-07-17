<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PollOptions Model
 *
 * @property \App\Model\Table\PollsTable|\Cake\ORM\Association\BelongsTo $Polls
 * @property \App\Model\Table\PollsTable|\Cake\ORM\Association\HasMany $PollVotes
 *
 * @method \App\Model\Entity\PollOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\PollOption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PollOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PollOption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PollOption|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PollOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PollOption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PollOption findOrCreate($search, callable $callback = null, $options = [])
 */
class PollOptionsTable extends Table
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

        $this->setTable('poll_options');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Polls', [
            'foreignKey' => 'poll_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('PollVotes', [
            'foreignKey' => 'option_id',
            'joinType' => 'INNER',
            "dependent" => true,
        ]);

        $this->hasOne('PollUserOptions', [
            'foreignKey' => 'option_id',
            "dependent" => true,
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
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('option_name')
            ->requirePresence('option_name', 'create')
            ->allowEmptyString('option_name', false);

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
        $rules->add($rules->existsIn(['poll_id'], 'Polls'));

        return $rules;
    }
}
