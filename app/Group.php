<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Group extends Model
{

    /**

     * Get the index name for the model.

     *

     * @return string

     */

    public $fillable = ['user_id', 'title', 'title_ru', 'status'];

    public function users() {

       return $this->belongsTo('App\GroupUsers', 'group_id');

    }

    public static function countUsers($groupId){

        $countUsers = GroupUsers::where('group_id', '=', $groupId)->count();

        return $countUsers;
    }

}