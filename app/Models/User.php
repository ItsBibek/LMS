<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $primaryKey = 'batch_no';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_name',
        'batch_no',
        'faculty',
        'email',
        'photo_path',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'batch_no';
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function currentIssues()
    {
        return $this->issues()->whereNull('return_date');
    }

    public function returnedIssues()
    {
        return $this->issues()->whereNotNull('return_date');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_batch_no', 'batch_no');
    }
}
