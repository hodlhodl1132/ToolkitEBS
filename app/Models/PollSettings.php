<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that can be modified for the model
     * 
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'duration',
        'interval',
        'automated_polls',
    ];

    /**
     * The models default values
     * 
     * @var array
     */
    protected $attributes = [
        'provider_id' => 'default',
        'duration' => 3,
        'interval' => 10,
        'automated_polls' => false,
    ];

    /**
     * Transform the resource into an array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'provider_id' => $this->provider_id,
            'duration' => $this->duration,
            'interval' => $this->interval,
            'automated_polls' => $this->automated_polls,
        ];
    }
}
