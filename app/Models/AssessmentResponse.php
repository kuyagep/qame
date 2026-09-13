<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResponse extends Model
{
    protected $fillable = ['assessment_id', 'user_id', 'total_score', 'percentage', 'is_passed', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_passed' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
