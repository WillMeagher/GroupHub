<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // returns a request 
    static function find($id) {
        return self::select('permissions.*', 'users.name AS user_name', 'users.id AS user_id', 'groups.name AS group_name', 'groups.id AS group_id', 
        'groups.creator_id AS group_creator_id')
        ->from('permissions')
        ->join('groups', 'permissions.group_id', '=', 'groups.id')
        ->join('users', 'permissions.user_id', '=', 'users.id')
        ->where('permissions.id', '=',  $id)
        ->first();
    }

    // gets all not viewed pending requests to groups owned by user and all not pending requests made by the user
    static function getNewNotifications($user_id) {
        return self::select('users.name AS user_name', 'groups.name AS group_name', 'groups.creator_id', 'permissions.id', 
        'permissions.user_id', 'permissions.group_id', 'permissions.message', 'permissions.status', 'permissions.updated_at')
        ->from('permissions')
        ->join('groups', 'permissions.group_id', '=', 'groups.id')
        ->join('users', 'permissions.user_id', '=', 'users.id')
        ->where('permissions.notify_user', '=', $user_id)
        ->where('permissions.viewed', '=', 0)
        ->orderBy('updated_at', 'desc')
        ->get();
    }

    // gets all viewed pending requests to groups owned by user and all not pending requests made by the user
    static function getOldNotifications($user_id) {
        return self::select('users.name AS user_name', 'groups.name AS group_name', 'groups.creator_id', 'permissions.id', 
        'permissions.user_id', 'permissions.group_id', 'permissions.message', 'permissions.status', 'permissions.updated_at')
        ->from('permissions')
        ->join('groups', 'permissions.group_id', '=', 'groups.id')
        ->join('users', 'permissions.user_id', '=', 'users.id')
        ->where('permissions.notify_user', '=',  $user_id)
        ->where('permissions.viewed', '=', 1)
        ->orderBy('updated_at', 'desc')
        ->get();
    }

    // checks to see if there exists a request for a user in a group 
    static function exists($user_id, $group_id) {
        return self::select('permissions.message')
        ->from('permissions')
        ->where('permissions.user_id', '=',  $user_id)
        ->where('permissions.group_id', '=', $group_id)
        ->exists();
    }

    // checks to see if there is an accpeted request for a particualar user in a group 
    static function isMember($user_id, $group_id) {
        return self::select('permissions.message')
        ->from('permissions')
        ->where('permissions.user_id', '=',  $user_id)
        ->where('permissions.group_id', '=', $group_id)
        ->where('permissions.status', '=', 'Accepted')
        ->exists();
    }

    // gets the owners id of a particular requests group
    static function owner($request_id) {
        return self::select('groups.creator_id')
        ->from('groups')
        ->join('permissions', 'permissions.group_id', '=', 'groups.id')
        ->where('permissions.id', '=', $request_id)
        ->first()
        ['creator_id'];
    }

    static function notificationsViewed($user_id) {
        self::select('permissions.*')
        ->join('groups', 'group_id', '=', 'groups.id')
        ->where('notify_user', '=',  $user_id)
        ->where('viewed', '=', 0)
        ->update(['viewed' => 1, 'updated_at' => self::raw('permissions.updated_at')]);
    }

    static function acceptPending($group) {
        self::select('permissions.*')
        ->where('permissions.group_id', '=', $group->id)
        ->where('permissions.status', '=', 'Pending')
        ->update(['viewed' => 0, 'notify_user' => $group->creator_id, 'status' => 'Accepted']);
    } 
}
