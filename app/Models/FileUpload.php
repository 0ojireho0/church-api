<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class FileUpload extends Model
{
    //

    // protected $connection = 'portal_request_db';
    protected $table = 'file_upload';

    protected $fillable = [
        'book_id',
        'filename',
        'filepath'
    ];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
