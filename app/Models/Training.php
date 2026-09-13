<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $fillable = [
        'title',
        'description',
        'venue',
        'date',
        'number_of_days',
        'end_of_training',
        'with_accommodation',
        'status'
    ];

    protected $casts = [
        'end_of_training'    => 'date:Y-m-d',
        'with_accommodation' => 'boolean',
        'number_of_days'     => 'integer',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }
}
