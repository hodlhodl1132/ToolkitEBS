<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'end_time',
        'poll_data'
    ];

    protected $casts = [
        'poll_data' => 'array'
    ];
}
