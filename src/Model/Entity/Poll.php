<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Poll Entity
 *
 * @property int $id
 * @property int $post_id
 * @property string $question
 * @property bool $user_add_options
 * @property int $expires
 * @property bool $multi
 * @property bool $redo
 *
 * @property \App\Model\Entity\Post $post
 * @property \App\Model\Entity\PollOption[] $poll_options
 * @property \App\Model\Entity\PollVote[] $poll_votes
 */
class Poll extends Entity
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
        'question' => true,
        'user_add_options' => true,
        'expires' => true,
        'post' => true,
        'poll_options' => true,
        'poll_votes' => true,
        'multi' => true,
        "redo" => true,
    ];
}
