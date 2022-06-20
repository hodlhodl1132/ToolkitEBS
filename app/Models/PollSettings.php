<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'poll_duration'
    ];
}