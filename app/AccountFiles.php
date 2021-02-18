<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountFiles extends Model
{
    //
    protected $fillable = [
        'account_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];

}