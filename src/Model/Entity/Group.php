<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string|null $description
 * @property int|null $description_by
 * @property int|null $image_by
 *
 * SQL Function/Calculated Fields
 * @property float $score
 * @property int $num_members
 * @property int $recent_time
 * @property bool $user_in_group
 * @property int $num_upcoming_events
 *
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\GroupMember[] $group_members
 */
class Group extends Entity
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
        'name' => true,
        'image' => true,
        'description' => true,
        'description_by' => true,
        'image_by' => true,
        'events' => true,
        'score' => true,
        'group_members' => true
    ];

}
