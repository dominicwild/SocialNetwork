<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property int $id
 * @property string $content
 * @property int|null $user_id
 * @property string $created_time
 * @property int|null $modified_time
 * @property int $post_type
 *
 * SQL Function Fields
 * @property float $score
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\UserPostNotification[] $user_post_notifications
 * @property \App\Model\Entity\Poll[] $polls
 */
class Post extends Entity {


    const TYPE_WALL = 1;
    const TYPE_EVENT = 2;
    const TYPE_GROUP = 3;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'content' => true,
        'user_id' => true,
        'user' => true,
        'created_time' => true,
        'modified_time' => true,
        "post_type" => true,
        'score' => true,
    ];

}
