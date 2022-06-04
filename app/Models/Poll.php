<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class Poll extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'provider_id',
        'end_time',
        'title',
        'options',
        'length'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Determines the prunable query
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        $currentTime = intval(Carbon::now()->timestamp) + 20;
        return $this->where('end_time', '<=', $currentTime);
    }
}
