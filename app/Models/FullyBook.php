<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FullyBook extends Model
{
    //

    protected $fillable = [
        'date',
        'church_id',
        'is_event',
        'event_name'
    ];
}
