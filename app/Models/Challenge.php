<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
    ];

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
