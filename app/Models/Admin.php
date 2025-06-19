<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Church;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Authenticatable
{
    //
    use Notifiable;

    protected $fillable = [
        'fullname',
        'username',
        'password',
        'email',
        'church_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }
}
