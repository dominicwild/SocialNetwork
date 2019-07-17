<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property int $post_id
 * @property int $group_id
 * @property string|null $place
 * @property int|null $date
 * @property string $title
 * @property string $image
 * @property int|null $end_date
 *
 * @property string $calendar_event_id
 *
 * SQL Function Fields
 * @property float $score
 *
 * @property \App\Model\Entity\Post $post
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\EventParticipant[] $event_participants
 */
class Event extends Entity
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
        'post_id' => true,
        'group_id' => true,
        'place' => true,
        'date' => true,
        'end_date' => true,
        'title' => true,
        'post' => true,
        'group' => true,
        'event_participants' => true,
        'image' => true,
        'score' => true,
    ];

    protected function _getCalendarEventId() {
        return "00000" . $this->id;
    }

}
