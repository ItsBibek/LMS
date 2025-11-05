<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_batch_no',
        'Accession_Number',
        'reserved_at',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_batch_no', 'batch_no');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'Accession_Number', 'Accession_Number');
    }

    public function getExpiresAtAttribute()
    {
        return $this->reserved_at ? $this->reserved_at->copy()->addHours(24) : null;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }
}
