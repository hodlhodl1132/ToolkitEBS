<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ahc\Jwt\JWT;

class JsonWebToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_user_id',
        'token'
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function __construct(string $provider_user_id)
    {
        $this->provider_user_id = $provider_user_id;

        $jwtFactory = new JWT(env('EBS_STREAMER_SECRET'), 'HS256');
        $token = $jwtFactory->encode([
            'twitch_provider_id' => $provider_user_id,
            'scopes' => ['broadcaster'],
            'exp' => time() + (60 * 60 * 24 * 90)
        ]);

        $this->token = $token;
        $this->save();
    }
}
