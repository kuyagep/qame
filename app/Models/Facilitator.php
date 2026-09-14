<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facilitator extends Model
{
    protected $fillable = ['name', 'email', 'position', 'office_division'];

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }
}
