<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingSession extends Model
{
    protected $fillable = [
        'training_id',
        'day',
        'title',
        'facilitator_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:Y-m-d\TH:i',
        'end_time'   => 'datetime:Y-m-d\TH:i',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(Facilitator::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }

    public function pretest()
    {
        return $this->hasOne(Assessment::class)->where('type', 'pretest');
    }

    public function posttest()
    {
        return $this->hasOne(Assessment::class)->where('type', 'posttest');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
