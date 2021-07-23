<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    static function getAllListed() {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.privacy', '!=', 'Delisted')
        ->get();
    }

    static function find($id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.id', '=', $id)
        ->first();
    }

    static function userCreatedGroups($user_id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.creator_id', '=', $user_id)
        ->get();
    }

    static function userJoinedGroups($user_id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->join('group_permissions', 'group_permissions.group_id', '=', 'groups.id')
        ->where('group_permissions.user_id', '=', $user_id)
        ->where('group_permissions.status', '=', 'accepted')
        ->get();
    }
}
