<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    static function find($name) {
    
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.name', '=', str_replace("_", " ", $name))
        ->first();
    }

    static function allListed() {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('groups.privacy', '!=', 'Delisted')
        ->get();
    }

    static function allCreated($username) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('users.name', '=', str_replace("_", " ", $username))
        ->get();
    }

    static function listedCreated($username) {
        return self::select('groups.*', 'users.name AS creator_name')
        ->from('groups')
        ->join('users', 'users.id', '=', 'groups.creator_id')
        ->where('users.name', '=', str_replace("_", " ", $username))
        ->where('groups.privacy', '!=', 'Delisted')
        ->get();
    }

    static function joined($username) {
        return self::select('groups.*', 'c.name as creator_name', 'users.name as member_name')
        ->from('groups')
        ->join('permissions', 'permissions.group_id', '=', 'groups.id')
        ->join('users', 'users.id', '=', 'permissions.user_id')
        ->join('users as c', 'c.id', '=', 'groups.creator_id')
        ->where('users.name', '=', str_replace("_", " ", $username))
        ->where('permissions.status', '=', 'Accepted')
        ->where('groups.privacy', '!=', 'Delisted')
        ->get();
    }

    static function incrementSize($id) {
        return self::select('groups.*')
        ->from('groups')
        ->where('groups.id', '=', $id)
        ->increment('groups.size', 1);
    }

}
