<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity.
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property string $body
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Project[] $projects
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\User[] $users
 */
class Ticket extends Entity
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
        '*' => true,
        'id' => false,
    ];
}
