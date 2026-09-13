<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = ['training_session_id', 'type', 'title', 'passing_score'];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class);
    }
}
