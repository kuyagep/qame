<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Yajra\Address\HasAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUlids, HasAddress;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Step 1: Personal Profile Info
        'prefix',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'name',
        'email',
        'mobile_number',
        'birthdate',
        'sex',
        'position',
        'religion',
        'disability',
        'ethnic_group',

        // Step 2: Address Structural Identifiers
        'office_id',
        'province_id',
        'city_id',
        'barangay_id',
        'street', // Used for your $request->purok, street value

        // Step 3: Application Access Rules
        'username',
        'password',
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    protected $appends = ['full_name'];

    /**
     * Get the participant's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->firstname} {$this->lastname}")
        );
    }

    /**
     * Relationship: User belongs to an Office/School
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Relationship: Events joined by the user (as a participant)
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
            ->withTimestamps();
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user', 'user_id', 'event_id')->withTimestamps();
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class)->withTimestamps();
    }
}
