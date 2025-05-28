<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //

    protected $fillable = [
        'user_id',
        'church_id',
        'wedding_rehearsal_id',
        'reference_num',
        'date',
        'time_slot',
        'service_type',
        'status',
        'form_data',
        'filename',
        'filepath',
        'mop'
    ];

    protected $casts = [
        'form_data' => 'array'
    ];
}
