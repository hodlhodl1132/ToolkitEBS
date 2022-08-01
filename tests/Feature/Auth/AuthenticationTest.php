<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Socialite;
use Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $abstractUser =  Mockery::mock(Laravel\Socialite\Two\User::class);
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(Str::random(10))
            ->shouldReceive('getName')
            ->andReturn('John Doe')
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10).'@example.com')
            ->shouldReceive('getToken')
            ->andReturn(Str::random(10))
            ->shouldReceive('getRefreshToken')
            ->andReturn(Str::random(10))
            ->shouldReceive('scopes')
            ->andReturn(['moderation:read']);

        Socialite::shouldReceive('driver')
            ->with('twitch')
            ->andReturn($abstractUser);

        $redirectResponse = $this->get('/auth/twitch/oauth/redirect')
            ->assertRedirectContains('id.twitch.tv/oauth2/authorize')
            ->assertRedirectContains(env('TWITCH_CLIENT_ID'))
            ->assertRedirectContains('moderation%3Aread');

        $this->get($redirectResponse->headers->get('Location'))
            ->assertRedirectContains('auth/twitch/oauth/authorized');

    }
}
