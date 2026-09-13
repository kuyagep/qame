<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'join_code',
        'description',
        'location',
        'start_date',
        'end_date',
        'capacity',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'capacity' => 'integer',
    ];

    // Automatically generate unique join code when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->join_code)) {
                $event->join_code = Str::random(5); // Generates e.g. "aX9kP"
            }
        });
    }

    // Accessor: Clean Date Display
    public function getFormattedDateRangeAttribute()
    {
        $start = $this->start_date;
        $end = $this->end_date;

        // If start and end are on the exact same day: "August 02, 2026 (8:00 AM - 5:00 PM)"
        if ($start->isSameDay($end)) {
            return $start->format('M d, Y') . ' (' . $start->format('h:i A') . ' - ' . $end->format('h:i A') . ')';
        }

        // If multi-day in same month: "Aug 02 - 03, 2026"
        if ($start->isSameMonth($end)) {
            return $start->format('M d') . ' - ' . $end->format('d, Y');
        }

        // If multi-day across different months: "Aug 30 - Sep 02, 2026"
        return $start->format('M d, Y') . ' - ' . $end->format('M d, Y');
    }


    // Accessor for complete URL
    public function getJoinUrlAttribute()
    {
        return route('events.join-by-link', $this->join_code);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')->withTimestamps();
    }

    public function isJoinedBy($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
