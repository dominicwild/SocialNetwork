<?php
namespace App\Model\Table;

use App\Model\Entity\Post;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groups Model
 *
 * @property \App\Model\Table\EventsTable|\Cake\ORM\Association\HasMany $Events
 * @property \App\Model\Table\GroupMembersTable|\Cake\ORM\Association\HasMany $GroupMembers
 * @property UsersTable $Users
 *
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Events', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('GroupMembers', [
            'foreignKey' => 'group_id',
            "dependent" => true,
        ]);
        $this->hasMany('GroupPosts', [
            'foreignKey' => 'group_id',
            "dependent" => true,
        ]);
        $this->hasOne('DescriptionBy', [
            'foreignKey' => 'id',
            'bindingKey' => 'description_by',
            "className" => "Users",
            "propertyName" => "description_by",
        ]);
        $this->hasOne('ImageBy', [
            'foreignKey' => 'id',
            'bindingKey' => 'image_by',
            "className" => "Users",
            "propertyName" => "image_by",
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('image')
            ->allowEmptyFile('image');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('description_by')
            ->allowEmptyString('description_by');

        $validator
            ->integer('image_by')
            ->allowEmptyFile('image_by');

        return $validator;
    }

    public function findRenderContent(Query $query, array $options) {

        $last_id = isset($options["last_id"]) ? $options["last_id"] : -1;

        $user_id = -1;
        if(isset($options["user"])){
            $user_id = $options["user"];
        }

        $exclude_ids = [-1];
        if(isset($options["exclude_ids"])){
            $exclude_ids = $options["exclude_ids"];
        }

        $query->contain([
            "Events" => [
                "finder" => [
                    "RenderContent" => [
                        "user" => $user_id
                    ]
                ],
                "queryBuilder" => function ($q) use ($exclude_ids, $last_id) {
                    if ($last_id != -1) {
                        $q->where(["Events.post_id <" => $last_id]);
                    }
                    return $q->limit(10)->where(["Events.id NOT IN" => $exclude_ids]);
                },
            ],
            "GroupMembers",
            "ImageBy",
            "DescriptionBy",
        ]);

        if(isset($options["group_id"])) {
            $query->where([
                "Groups.id" => $options["group_id"]
            ]);
        }

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use ($user_id) {
            foreach($results as $result) {
                $result->user_in_group = $this->userInGroup($result->group_members, $user_id);
            }
            return $results;
        });

        return $query;
    }


    public function findGroupCard(Query $query, array $options) {

        $user_id = -1;
        if(isset($options["user"])){
            $user_id = $options["user"];
        }

        $query->contain([
            "Events" => [
                "Posts",
                "queryBuilder" => function ($q) {
                    return $q->order(["Posts.created_time" => "DESC"]);
                },
            ],
            "GroupPosts" => [
                "Posts",
                "queryBuilder" => function($q) {
                    return $q->order(["Posts.created_time" => "DESC"]);
                }
            ],
            "GroupMembers",
        ]);

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use ($user_id) {
            foreach($results as $result) {
                $recent_time = -1;
                if(isset($result->group_posts[0]->post->created_time)){
                    $recent_time = $result->group_posts[0]->post->created_time;
                }
                if(isset($result->events[0]->post->created_time)
                    && $result->events[0]->post->created_time > $recent_time){
                    $recent_time = $result->events[0]->post->created_time;
                }

                $result->recent_time = $recent_time;
                $result->user_in_group = $this->userInGroup($result->group_members, $user_id);
                $result->num_members = count($result->group_members);
            }
            return $results;
        });

        return $query;
    }

    public function findSearchResult(Query $query, array $options) {
        $query = $this->findGroupCard($query,$options);

        $search = $options["search"];

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
            foreach($results as $result) {
                $count = 0;
                $time = time();
                foreach($result->events as $event){
                    if($event->post->created_time > $time){
                        $count++;
                    } else {
                        break; //As in descending order from GroupCard
                    }
                }
                $result->num_upcoming_events = $count;
            }
            return $results;
        });

        $query->select(["score" => "MATCH (Groups.name, Groups.description) AGAINST (:search)"])
            ->enableAutoFields(true)
            ->where("MATCH (Groups.name, Groups.description) AGAINST(:search)")
            ->bind(":search", $search)
            ->order(["score" => "DESC"]);

        return $query;
    }

    private function userInGroup($members, $user_id){
        foreach($members as $member){
            if($user_id == $member->user_id){
                return true;
            }
        }
        return false;
    }


}
