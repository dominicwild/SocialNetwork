<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PollVote Entity
 *
 * @property int $poll_id
 * @property int $user_id
 * @property int $option_id
 *
 * @property \App\Model\Entity\Poll $poll
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\PollOption $poll_option
 */
class PollVote extends Entity
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
        'poll' => true,
        'user' => true,
        'poll_option' => true
    ];
}
