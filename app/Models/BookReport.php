<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReport extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'issue_type',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
