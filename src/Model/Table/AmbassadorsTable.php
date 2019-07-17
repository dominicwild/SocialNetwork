<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ambassadors Model
 *
 * @method \App\Model\Entity\Ambassador get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ambassador newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Ambassador[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ambassador|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ambassador|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ambassador patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ambassador[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ambassador findOrCreate($search, callable $callback = null, $options = [])
 */
class AmbassadorsTable extends Table
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

        $this->setTable('ambassadors');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey('user_id');
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
            ->integer('user_id')
            ->allowEmptyString('user_id', 'create');

        $validator
            ->integer('remind_time')
            ->requirePresence('remind_time', 'create')
            ->allowEmptyString('remind_time', false);

        return $validator;
    }
}
