<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PollUserOptions Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PollOptionsTable|\Cake\ORM\Association\BelongsTo $PollOptions
 *
 * @method \App\Model\Entity\PollUserOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\PollUserOption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PollUserOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PollUserOption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PollUserOption|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PollUserOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PollUserOption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PollUserOption findOrCreate($search, callable $callback = null, $options = [])
 */
class PollUserOptionsTable extends Table
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

        $this->setTable('poll_user_options');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey(['user_id', 'option_id']);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PollOptions', [
            'foreignKey' => 'option_id',
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['option_id'], 'PollOptions'));

        return $rules;
    }
}
