<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    //

    // protected $connection = 'portal_request_db';
    // protected $table = 'app_portal_callback';

    protected $fillable = [
        'church_name',
        'address',
        'city',
        'phone',
        'landline',
        'email',
        'img',
        'img_path'
    ];
}
