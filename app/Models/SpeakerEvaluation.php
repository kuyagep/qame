<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakerEvaluation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
