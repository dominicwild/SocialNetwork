<?php
namespace App\Model\Table;

use App\Model\Entity\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\BelongsTo $Posts
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\EventParticipantsTable|\Cake\ORM\Association\HasMany $EventParticipants
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 */
class EventsTable extends Table {

    private $deleted_calendar_event_id;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Posts', [
            'foreignKey' => 'post_id',
            'joinType' => 'INNER',
            "dependent" => "true",
            "order" => ["created_time" => "DESC"],
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('EventParticipants', [
            'foreignKey' => 'event_id',
            "dependent" => "true",
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
            ->integer('group_id');

        $validator
            ->scalar('place')
            ->maxLength('place', 255)
            ->allowEmptyString('place');

        $validator
            ->integer('date')
            ->allowEmptyString('date');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->allowEmptyString('title', false);

        return $validator;
    }

    public function findRenderContent(Query $query, array $options) {

        $user_id = -1;
        if(isset($options["user"])){
            $user_id = $options["user"];
        }

        $query
            ->contain([
                "EventParticipants" => [
                    "Users",
                ],
                "Posts" => [
                    "finder" => [
                        "RenderContent" => ["user" => $user_id]
                    ]
                ]
            ])
            ->order([
                "Posts.created_time" => "DESC"
            ]);

        if (isset($options["limit"]) && $options["limit"] > 0) {
            $query->limit($options["limit"]);
        }

        $conditions = [];

        if(isset($options["last_id"])) {
            $last_id = $options["last_id"];
            $event = $this->find()->where(["Events.post_id" => $last_id])->contain(["Posts"])->first();
            if ($event != []) {
                $last_time = $event->post->created_time;
                $conditions["Posts.created_time <="] = $last_time;
                $conditions["Posts.id !="] = $options["last_id"];
                $query->where($conditions);
            }
        }

        $query->formatResults($this->eventCardData($user_id));

        return $query;
    }

    public function findEventCard(Query $query, array $options) {

        $user_id = -1;
        if(isset($options["user"])){
            $user_id = $options["user"];
        }

        $query->contain([
            "EventParticipants" => [
                "Users",
            ],
            "Posts",
            "Groups",
        ]);

        $query->order([
            "Events.date" => "ASC"
        ]);

        $query->formatResults($this->eventCardData($user_id));

        return $query;
    }

    private function eventCardData($user_id){
        return function(\Cake\Collection\CollectionInterface $results) use ($user_id){
            foreach($results as $result) {
                $in_event = false;
                foreach($result->event_participants as $participant){
                    if($user_id == $participant->user_id){
                        $in_event = true;
                        break;
                    }
                }
                $result->user_in_event = $in_event;
                $result->amount_at_event = count($result->event_participants);
            }
            return $results;
        };
    }

    public function findSearchResult(Query $query, array $options) {
        $search = $options["search"];

        $query->contain(["Posts", "Groups"])
            ->select(["score" => "(MATCH (Events.title, Events.place) AGAINST (:search)) + (MATCH (Posts.content) AGAINST(:search))"])
            ->enableAutoFields(true)
            ->where("MATCH (Events.title, Events.place) AGAINST(:search) OR MATCH (Posts.content) AGAINST(:search)")
            ->bind(":search", $search)
            ->order(["score" => "DESC"]);

        return $query;
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
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }

    public function beforeDelete($event, $entity, $options) {
        $this->deleted_calendar_event_id = $entity->calendar_event_id;
    }

    public function afterDelete(){
        $QueuedJobs = TableRegistry::get("Queue.QueuedJobs");
        $data = [
            "calendar_event_id" => $this->deleted_calendar_event_id,
        ];
        $QueuedJobs->createJob('RemoveGoogleCalendarEvent',$data, ["notBefore" => time()+60*5]);
    }

}
