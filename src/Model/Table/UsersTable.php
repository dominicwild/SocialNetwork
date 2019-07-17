<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Posts', [
            "className" => "Posts",
            "dependent" => "true",
        ]);
        $this->hasMany('UserPostNotifications', [
            "foreignKey" => "user_id",
            "dependent" => "true",
        ]);
        $this->hasOne("Ambassadors",[
            "foreignKey" => "user_id",
            "dependent" => "true",
        ]);
    }

//    public function findRenderContent(Query $query, array $options) {
//
//        $query = $query
//            ->contain(["Users"])
//            ->find("all");
//
//        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
//            foreach($results as $result) {
//
//            }
//            return $results;
//        });
//
//        return $query;
//    }

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
            ->scalar('FirstName')
            ->maxLength('FirstName', 255)
            ->requirePresence('FirstName', 'create')
            ->allowEmptyString('FirstName', false);

        $validator
            ->scalar('LastName')
            ->maxLength('LastName', 255)
            ->requirePresence('LastName', 'create')
            ->allowEmptyString('LastName', false);

        $validator
            ->scalar('OAuth')
            ->maxLength('OAuth', 255)
            ->requirePresence('OAuth', 'create')
            ->allowEmptyString('OAuth', false);

        $validator
            ->scalar('Email')
            ->maxLength('Email', 255)
            ->requirePresence('Email', 'create')
            ->allowEmptyString('Email', false);

        $validator
            ->scalar('Permissions')
            ->maxLength('Permissions', 255)
            ->requirePresence('Permissions', 'create')
            ->allowEmptyString('Permissions', false);

        return $validator;
    }


    function beforeSave(Event $event, User $user, ArrayObject $options) {
        $key = Configure::read("EncryptionKey");

        $user->access_token = base64_encode(Security::encrypt($user->access_token,$key));
        $user->refresh_token = base64_encode(Security::encrypt($user->refresh_token,$key));
    }

    public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
        $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
            $key = Configure::read("EncryptionKey");
            foreach($results as $result) {
                if ($result instanceof User) {
                    if($result->access_token != null || $result->access_token != "") {
                        $result->access_token = Security::decrypt(base64_decode($result->access_token), $key);
                    }
                    if($result->refresh_token != null || $result->refresh_token != "") {
                        $result->refresh_token = base64_decode($result->refresh_token);
                        $result->refresh_token = Security::decrypt($result->refresh_token, $key);
                    }
                }
            }
            return $results;
        });
    }

}
