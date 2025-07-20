<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Church;

class Certificate extends Model
{
    //

    protected $table = 'certificate_files';

    protected $fillable = [
        'church_id',
        'fullname',
        'filename',
        'filepath',
        'dob',
        'cert_type'
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }
}
