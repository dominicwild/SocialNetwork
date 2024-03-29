<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserPostNotification Entity
 *
 * @property int $user_id
 * @property int $post_id
 * @property bool $notifications
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Post $post
 */
class UserPostNotification extends Entity
{

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
        'notifications' => true,
        'user' => true,
        'post' => true
    ];
}
