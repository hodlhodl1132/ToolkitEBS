<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'provider_id',
        'password',
        'provider_token',
        'refresh_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function personalAccessToken()
    {
        return $this->hasOne(PersonalAccessToken::class);
    }

    public function getChannelPermissions()
    {
        $allPermissions = $this->getDirectPermissions()->toArray();
        $permissionData = [];
        
        if ($channelPermissions = preg_grep('/\d+$/', array_column($allPermissions, 'name')))
        {
            foreach ($channelPermissions as $value) {
                preg_match('/\d+$/', $value, $matches);
                $providerId = $matches[0];
                if ($user = User::where('provider_id', $providerId)->first())
                {
                    array_push($permissionData, [
                            'provider_id' => $providerId,
                            'user' => $user
                        ]);    
                }
            }
        }

        return $permissionData;
    }

    public function hasWildcardChannelPermission(Permission $permission)
    {
        if (!preg_match('/\d+$/', $permission->name, $matches))
            return false;

        $providerId = $matches[0];

        if (!intval($providerId))
            return false;

        if ($this->provider_id == $providerId)
            return true;

        return $this->hasPermissionTo($permission);
    }

    public function isWildcardPermissionOwner(Permission $permission)
    {
        if (!preg_match('/\d+$/', $permission->name, $matches))
            return false;
        
        $providerId = $matches[0];

        if (!intval($providerId))
            return false;

        return $this->provider_id == $providerId;
    }
}
