<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Center extends Model
{
    protected $fillable = [
        'name',
        'address',
        'slug',
        'phone',
        'status'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function examSessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }
}