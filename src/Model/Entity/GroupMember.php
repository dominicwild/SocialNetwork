<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GroupMember Entity
 *
 * @property int $user_id
 * @property int $group_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Group $group
 */
class GroupMember extends Entity
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
        'user_id' => true,
        'group_id' => true,
        'user' => true,
        'group' => true
    ];
}
