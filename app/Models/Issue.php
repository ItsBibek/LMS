<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_batch_no',
        'Accession_Number',
        'issue_date',
        'due_date',
        'return_date',
        'fine',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_batch_no', 'batch_no');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'Accession_Number', 'Accession_Number');
    }
}
