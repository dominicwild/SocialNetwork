<?php
namespace App\Model\Table;

use App\Model\Entity\Event;
use App\Model\Entity\Group;
use App\Model\Entity\GroupPost;
use Cake\Collection\Collection;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GroupMembers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 *
 * @method \App\Model\Entity\GroupMember get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupMember newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupMember[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupMember|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupMembersTable extends Table
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

        $this->setTable('group_members');
        $this->setDisplayField('group_id');
        $this->setPrimaryKey(["user_id","group_id"]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }

    public function findUserGroups(Query $query, array $options){
        $user_id = $options["id"];
        $own_user_id = $options["user"];
        $event_limit = isset($options["event_limit"]) ? $options["event_limit"] : -1;
        $group_post_limit = isset($options["group_post_limit"]) ? $options["group_post_limit"] : -1;
        $last_event_id = isset($options["last_event_id"]) ? $options["last_event_id"] : -1;
        $last_post_id = isset($options["last_post_id"]) ? $options["last_post_id"] : -1;

        $query
            ->contain([
                "Groups" => [
                    "finder" => [
                        "GroupCard" => [
                            "user" => $own_user_id,
                        ]
                    ],
                    "Events" => [
                        "finder" => [
                            "RenderContent" => [
                                "user" => $own_user_id,
                                "limit" => $event_limit,
                                "last_id" => $last_event_id,
                            ]
                        ],
                        "queryBuilder" => function ($q) use ($last_post_id) {
                            if ($last_post_id != -1) {
                                $q->where(["Events.post_id <" => $last_post_id]);
                            }
                            return $q;
                        },
                    ],
                    "GroupPosts" => [
                        "finder" => [
                            "RenderContent" => [
                                "user" => $own_user_id,
                                "limit" => $group_post_limit,
                                "last_id" => $last_post_id,
                            ]
                        ],
                        "queryBuilder" => function ($q) use ($last_post_id) {
                            if ($last_post_id != -1) {
                                $q->where(["GroupPosts.post_id <" => $last_post_id]);
                            }
                            return $q;
                        },
                    ],
                ]
            ])
            ->where(["GroupMembers.user_id" => $user_id]);

//        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use ($user_id) {
//            $groups = [];
//            $events = [];
//            $group_posts = [];
//            foreach($results as $member){
//                $group = $member->group;
//                $groups[] = $group;
//                $events = array_merge($events,$group->events);
//                $group_posts = array_merge($group_posts,$group->group_posts);
//            }
//
//            usort($events, function(Event $a,Event $b){
//                return -($a->post->created_time - $b->post->created_time);
//            });
//
////            debug($group_posts);
//
//            usort($group_posts, function(GroupPost $a,GroupPost $b){
//                return -($a->post->created_time - $b->post->created_time);
//            });
//
//            usort($groups, function(Group $a,Group $b){
//                return -($a->recent_time - $b->recent_time);
//            });
//
//            $newResults = new Collection([$groups,$events,$group_posts]);
//
//            return $newResults;
//        });

        return $query;
    }

}
