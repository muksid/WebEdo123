<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.12.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class MesType extends Model
{
    //
    protected $fillable = [
        'title',
        'message_type'
    ];

}