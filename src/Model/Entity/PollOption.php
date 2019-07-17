<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PollOption Entity
 *
 * @property int $id
 * @property int $poll_id
 * @property string $option_name
 *
 * @property \App\Model\Entity\Poll $poll
 * @property \App\Model\Entity\PollVote $poll_votes
 * @property \App\Model\Entity\PollUserOption $poll_user_option
 */
class PollOption extends Entity
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
        'poll_id' => true,
        'option_name' => true,
        'poll' => true
    ];
}
