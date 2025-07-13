<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Church;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FileUpload;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'church_id',
        'wedding_rehearsal_id',
        'reference_num',
        'date',
        'time_slot',
        'service_type',
        'status',
        'set_status',
        'form_data',
        'filename',
        'filepath',
        'mop',
        'mop_status',
        'book_type',
        'remarks',
        'reservation_type',
        'walkin_name'
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function files()
    {
        return $this->hasMany(FileUpload::class, 'book_id');
    }


}
