<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppRepAccountFiles extends Model
{
    //
    protected $fillable = [
        'rep_account_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];

}