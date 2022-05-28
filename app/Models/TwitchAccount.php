<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ahc\Jwt\JWT;
use Illuminate\Support\Facades\Log;

class TwitchAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'provider_user_id',
    ];

    public function JsonWebToken($provider_user_id) : string
    {
        $jwt = JsonWebToken::where('provider_user_id', $provider_user_id)->first();

        if ($jwt == null) {
            $jwt = new JsonWebToken($provider_user_id);
        }

        return $jwt->token;
    }
}
