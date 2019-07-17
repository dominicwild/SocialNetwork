<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Activity Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property string $link
 * @property int $time
 *
 * @property \App\Model\Entity\User $user
 */
class Activity extends Entity {

//    public static function activity($value = null) {
//        $options = array(
//            self::POST_ADD => __('POST_ADD',true),
//            self::POST_EDIT => __('POST_EDIT',true),
//            self::COMMENT_ADD => __('statusRead',true),
//            self::COMMENT_EDIT => __('statusAnswered',true),
//            self::EVENT_ADD => __('statusDeleted',true),
//            self::EVENT_EDIT => __('statusDeleted',true),
//            self::GROUP_ADD => __('statusDeleted',true),
//            self::GROUP_EDIT => __('statusDeleted',true),
//        );
//        return parent::enum($value, $options);
//    }

    const POST_ADD = 1;
    const POST_EDIT = 2;
    const COMMENT_ADD = 3;
    const COMMENT_EDIT = 4;
    const EVENT_ADD = 5;
    const EVENT_EDIT = 6;
    const GROUP_ADD = 7;
    const GROUP_EDIT = 8;
    const PROFILE_EDIT = 9;

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
        'description' => true,
        'link' => true,
        'time' => true,
        'user' => true
    ];
}
