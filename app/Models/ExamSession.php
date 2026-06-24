<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    protected $fillable = [
        'center_id',
        'title',
        'level',
        'module_max_score',
        'passing_percentage',
        'exam_date',
        'published'
    ];

    protected $casts = [
        'published' => 'boolean',
        'exam_date' => 'date'
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}