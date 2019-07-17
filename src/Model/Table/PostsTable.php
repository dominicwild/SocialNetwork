<?php
namespace App\Model\Table;

use App\View\Helper\MiscellaneousHelper;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Emojione\Client;
use Emojione\Ruleset;

/**
 * Posts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 */
class PostsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable("posts");
        $this->setDisplayField("id");
        $this->setPrimaryKey("id");

        $this->belongsTo('Users', [
            "foreignKey" => "user_id"
        ]);

        $this->hasMany("Comments", [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
        $this->hasMany("PostImages", [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
        $this->hasOne("GroupPosts", [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
        $this->hasOne("Events", [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
        $this->hasMany('UserPostNotifications', [
            "foreignKey" => "post_id",
            "dependent" => "true"
        ]);
        $this->hasMany('Polls', [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
        $this->hasMany('ReportedPosts', [
            "foreignKey" => "post_id",
            "dependent" => "true",
        ]);
    }

    public function findComments(Query $query, array $options) {

        if (isset($options['commentLimit'])) {
            $limit = $options['commentLimit'];
        } else {
            $limit = null;
        }

        $query = $query
            ->contain([
                "Users",
                "Comments.Users",
                "PostImages",
                "Comments" => function ($query) use ($limit) {
                    if (isset($limit)) {
                        $query = $query->limit($limit);
                    }
                    return $query->order(["comments.created_time" => "ASC"]);
                }
            ]);

        if(isset($options['commentLimit'])) {
            $post_id = $options['post_id'];
            return $query->where(["posts.id" => $post_id]);
        } else{
            return $query;
        }
    }

    public function findRenderContent(Query $query, array $options) {

        $user = -1;
        if(isset($options["user"])){
            $user = $options["user"];
        }

        $query = $query
            ->contain([
                "Users",
                "PostImages",
                "ReportedPosts",
                "UserPostNotifications" => [
                    "queryBuilder" => function(Query $q) use ($user){
                        return $q->where(["user_id" => $user]);
                    }
                ],
                "Comments" =>[
                    "Users",
                    "queryBuilder" => function($q){
                        return $q->order(["Comments.created_time" => "ASC"]);
                    }
                ],
                "Polls" => [
                    "finder"=> [
                        "RenderContent" => [
                            "user" => $user,
                        ],
                    ]
                ],
            ])
            ->order([
                "Posts.created_time" => "DESC"
            ]);

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use($user){
            foreach($results as $result) {
                if (isset($result->user_post_notifications[0]->notifications)) {
                    $result->hasNotification = $result->user_post_notifications[0]->notifications;
                } else {
                    $result->hasNotification = false;
                }

                $result->hasReported = false;
                foreach($result->reported_posts as $report){
                    if($report->user_id == $user){
                        $result->hasReported = true;
                        break;
                    }
                }
            }
            return $results;
        });

       return $query;
    }

    public function findSearchResult(Query $query, array $options) {
        $search = $options["search"];

        $query->contain(["Comments","Users"])
            ->select(["score" => "MATCH (Posts.content) AGAINST (:search)"])
            ->enableAutoFields(true)
            ->where(["MATCH (Posts.content) AGAINST(:search)","Posts.post_type" => "1"])
            ->bind(":search", $search)
            ->order(["score" => "DESC"]);

        return $query;
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
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->allowEmptyString('content', false);

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

        return $rules;
    }
}
