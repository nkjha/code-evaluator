<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'language',
        'code',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
