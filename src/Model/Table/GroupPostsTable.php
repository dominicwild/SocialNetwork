<?php
namespace App\Model\Table;

use App\Model\Entity\GroupPost;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GroupPosts Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\BelongsTo $Posts
 *
 * @method \App\Model\Entity\GroupPost get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupPost newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupPost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupPost|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupPost|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupPost[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupPost findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupPostsTable extends Table
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

        $this->setTable('group_posts');
        $this->setDisplayField('group_id');
        $this->setPrimaryKey(['group_id', 'post_id']);

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Posts', [
            'foreignKey' => 'post_id',
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
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['post_id'], 'Posts'));

        return $rules;
    }

    public function findRenderContent(Query $query, array $options) {

        $user_id = -1;
        if(isset($options["user"])){
            $user_id = $options["user"];
        }

        $last_id = isset($options["last_id"]) ? $options["last_id"] : -1;

        $exclude_ids = [-1];
        if(isset($options["exclude_ids"])){
            $exclude_ids = $options["exclude_ids"];
        }

        $query->contain([
            'Posts' => [
                "finder" => [
                    "RenderContent" => ["user" => $user_id]
                ]
            ]
        ]);

        $query->order([
            "Posts.created_time" => "DESC"
        ]);

        if (isset($options["limit"]) && $options["limit"] > 0) {
            $query->limit($options["limit"]);
        }

        //Where conditions
        $conditions = [
            "post_id NOT IN" => $exclude_ids,
        ];

        if(isset($options["group_id"])){
            $conditions["group_id"] = $options["group_id"];
        }



        if($last_id != -1){
            $conditions["GroupPosts.post_id <"] = $last_id;
        }
//        if(isset($options["last_id"])) {
//            debug("id set");
//            $last_id = $options["last_id"];
//            $group_post = $this->find()->where(["GroupPosts.post_id" => $last_id])->contain(["Posts"])->first();
//            debug("id set: " . $last_id);
//            debug("group_post: " . $group_post);
//            if ($group_post != []) {
//                $last_time = $group_post->post->created_time;
//                $conditions["Posts.created_time <="] = $last_time;
//                $conditions["Posts.id !="] = $last_id;
//                debug("last_id: " . $last_id);
//                debug("last_time: " . $last_time);
//            }
//        }

        $query->where($conditions);

        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) use ($user_id) {
            foreach($results as $result) {

            }
            return $results;
        });

        return $query;
    }

}
