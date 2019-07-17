<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $FirstName
 * @property string $LastName
 * @property string $google_id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $Email
 * @property string $Permissions
 * @property string $gender
 * @property string $department
 * @property string $profile_image
 * @property string $about_me
 * @property string $status
 * @property string $role
 * @property boolean $weekly_event_email
 * @property boolean $email_per_event
 * @property int $comment_notification_option
 * @property bool $auto_post_subscribe
 */
class User extends Entity
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
        'FirstName' => true,
        'LastName' => true,
        'OAuth' => true,
        'Email' => true,
        'Permissions' => true,
        'gender' => true,
        'department' => true,
        'profile_image' => true,
        'about_me' => true,
        'status' => true,
        'role' => true,
        'google_id'=> true,
        'access_token' => true,
        'refresh_token' => true,
        "weekly_event_email" => true,
        "email_per_event" => true,
        "comment_notification_option" => true,
        "auto_post_subscribe" => true,
    ];
}
