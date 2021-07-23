<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    use HasFactory;

    static function getAll($user_id) {
        /*
        SELECT `groups`.`name`, `group_permissions`.`group_id`, `group_permissions`.`user_id`, `group_permissions`.`message` 
        FROM `group_permissions` 
        JOIN `groups` ON `group_permissions`.`group_id`=`groups`.`id` 
        JOIN `users` ON `group_permissions`.`user_id`=`users`.`id` 
        WHERE `groups`.`user_id` = $user_id 
        AND `group_permissions`.`status` = 'pending'
         */

        return self::select('users.name AS user_name', 'groups.name AS group_name', 'group_permissions.message', 'group_permissions.id', 'group_permissions.created_at')
        ->from('group_permissions')
        ->join('groups', 'group_permissions.group_id', '=', 'groups.id')
        ->join('users', 'group_permissions.user_id', '=', 'users.id')
        ->where('groups.creator_id', '=',  $user_id)
        ->where('group_permissions.status', '=', 'pending')
        ->get();
    }

    static function getNewNotifications($user_id) {
        return self::select('groups.name AS group_name', 'group_permissions.group_id', 'group_permissions.status', 'group_permissions.updated_at')
        ->from('group_permissions')
        ->join('groups', 'group_permissions.group_id', '=', 'groups.id')
        ->where('group_permissions.user_id', '=',  $user_id)
        ->where('group_permissions.status', '!=', 'Pending')
        ->where('group_permissions.notify', '=', 1)
        ->get();
    }

    static function getOldNotifications($user_id) {
        return self::select('groups.name AS group_name', 'group_permissions.group_id', 'group_permissions.status', 'group_permissions.updated_at')
        ->from('group_permissions')
        ->join('groups', 'group_permissions.group_id', '=', 'groups.id')
        ->where('group_permissions.user_id', '=',  $user_id)
        ->where('group_permissions.status', '!=', 'Pending')
        ->where('group_permissions.notify', '!=', 1)
        ->get();
    }

    static function exists($user_id, $group_id) {
        /**
         * SELECT `group_permissions`.`message` 
         * FROM `group_permissions` 
         * WHERE `group_permissions`.`user_id` = $user_id
         * AND `group_permissions`.`group_id` = $group_id
         */

        return self::select('group_permissions.message')
        ->from('group_permissions')
        ->where('group_permissions.user_id', '=',  $user_id)
        ->where('group_permissions.group_id', '=', $group_id)
        ->exists();
    }

    static function isMember($user_id, $group_id) {

        return self::select('group_permissions.message')
        ->from('group_permissions')
        ->where('group_permissions.user_id', '=',  $user_id)
        ->where('group_permissions.group_id', '=', $group_id)
        ->where('group_permissions.status', '=', 'Accepted')
        ->exists();
    }

    static function find($id) {
        return self::select('users.name AS user_name', 'users.id AS user_id', 'groups.name AS group_name', 'groups.id AS group_id', 
        'groups.creator_id AS group_creator_id', 'group_permissions.message', 'group_permissions.id', 'group_permissions.created_at')
        ->from('group_permissions')
        ->join('groups', 'group_permissions.group_id', '=', 'groups.id')
        ->join('users', 'group_permissions.user_id', '=', 'users.id')
        ->where('group_permissions.id', '=',  $id)
        ->first();
    }

    static function owner($request_id) {
        /**
         * SELECT `groups`.`creator_id` 
         * FROM `groups` 
         * JOIN `group_permissions` ON `group_permissions`.`group_id`=`groups`.`id` 
         * WHERE `group_permissions`.`id` = $group_id
         */

        return self::select('groups.creator_id')
        ->from('groups')
        ->join('group_permissions', 'group_permissions.group_id', '=', 'groups.id')
        ->where('group_permissions.id', '=', $request_id)
        ->first()
        ['creator_id'];
    }

    static function notificationsViewed($user_id) {
        self::select('group_permissions.*')
        ->where('user_id', '=',  $user_id)
        ->where('status', '!=',  'Pending')
        ->where('notify', '=', 1)
        ->update(['notify' => 0]);
    }

    static function size($group_id) {
        return self::select('group_permissions.id')
        ->from('group_permissions')
        ->where('group_id', '=',  $group_id)
        ->where('status', '=',  'Accepted')
        ->get()
        ->count() + 1;
    }

    static function deleteGroup($group_id) {
        self::where('group_id', '=', $group_id)->delete();
    }
}
