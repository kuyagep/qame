<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $fillable = ['session_id', 'title', 'learning_objectives', 'order'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }
}
