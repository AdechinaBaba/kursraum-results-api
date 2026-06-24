<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_session_id',
        'table_number',
        'full_name',
        'lesen',
        'hoeren',
        'schreiben',
        'sprechen'
    ];

    protected $appends = [
        'total',
        'status'
    ];
    
    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function getTotalAttribute()
    {
        return
            $this->lesen +
            $this->hoeren +
            $this->schreiben +
            $this->sprechen;
    }

    public function getPassingScoreAttribute()
    {
        return (
            $this->examSession->module_max_score * 4
        ) / 2;
    }

    public function getStatusAttribute()
    {
        return $this->total >= $this->passing_score
            ? 'Bestanden'
            : 'Nicht Bestanden';
    }

    
}