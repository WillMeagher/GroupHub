<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    static function find($id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.id', '=', $id)
        ->first();
    }

    static function allListed() {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.privacy', '!=', 'Delisted')
        ->get();
    }

    static function created($user_id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.creator_id', '=', $user_id)
        ->get();
    }

    static function joined($user_id) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->join('permissions', 'permissions.group_id', '=', 'groups.id')
        ->where('permissions.user_id', '=', $user_id)
        ->where('permissions.status', '=', 'accepted')
        ->get();
    }

    static function incrementSize($id) {
        return self::select('groups.*')
        ->from('groups')
        ->where('groups.id', '=', $id)
        ->increment('groups.size', 1);
    }

}
