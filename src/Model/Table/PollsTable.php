<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Polls Model
 *
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\BelongsTo $Posts
 * @property \App\Model\Table\PollOptionsTable|\Cake\ORM\Association\HasMany $PollOptions
 * @property \App\Model\Table\PollVotesTable|\Cake\ORM\Association\HasMany $PollVotes
 *
 * @method \App\Model\Entity\Poll get($primaryKey, $options = [])
 * @method \App\Model\Entity\Poll newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Poll[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Poll|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Poll|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Poll patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Poll[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Poll findOrCreate($search, callable $callback = null, $options = [])
 */
class PollsTable extends Table
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

        $this->setTable('polls');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Posts', [
            'foreignKey' => 'post_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('PollOptions', [
            'foreignKey' => 'poll_id',
            "dependent" => true,
        ]);
        $this->hasMany('PollVotes', [
            'foreignKey' => 'poll_id',
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
            ->scalar('question')
            ->requirePresence('question', 'create')
            ->allowEmptyString('question', false);

        $validator
            ->boolean('user_add_options')
            ->requirePresence('user_add_options', 'create')
            ->allowEmptyString('user_add_options', false);

        $validator
            ->integer('expires')
            ->requirePresence('expires', 'create')
            ->allowEmptyString('expires', false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['post_id'], 'Posts'));

        return $rules;
    }

    public function findRenderContent(Query $query, array $options) {

        $user_id = isset($options["user"]) ? $options["user"] : -1;

        $query = $query
            ->contain([
                "PollOptions" => [
                    "PollVotes" => [
                        "Users",
                    ],
                    "PollUserOptions",
                ],
                "PollVotes",
            ]);

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use ($user_id){
            foreach($results as $poll) {
                $total_votes = 0;
                foreach($poll->poll_options as $option) {

                    foreach ($option->poll_votes as $vote) {
                        //Set has_voted & voted_for
                        if ($vote->user->id == $user_id) {
                            $poll->has_voted = true;
                            $option->voted_for = true;
                        }
                        $total_votes++;
                    }
                    if(!isset($option->voted_for)){
                        $option->voted_for = false;
                    }
                    //Set percent
                    if (count($poll->poll_votes) != 0) {
                        $option->percent = (count($option->poll_votes) / count($poll->poll_votes))*100;
                        $has_votes = true;
                    } else {
                        $option->percent = 0;
                    }
                    //Set is_users_option
                    if($option->poll_user_option != null && $option->poll_user_option->user_id == $user_id){
                        $option->is_user_option = true;
                    } else {
                        $option->is_user_option = false;
                    }
                }
                //Set has_voted default
                if(!isset($poll->has_voted)){
                    $poll->has_voted = false;
                }

                //Set max_percent
                if (count($poll->poll_votes) > 0) {
                    $max = 0;
                    foreach ($poll->poll_options as $option) {
                        if($max < $option->percent){
                            $max = $option->percent;
                        }
                    }
                    $poll->max_percent = $max;
                } else {
                    $poll->max_percent = 0;
                }
                $poll->total_votes = $total_votes;
            }

            return $results;
        });

        return $query;
    }
}
