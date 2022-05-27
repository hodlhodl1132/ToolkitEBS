<?php

namespace App\Http\Controllers;

use App\Events\ClientHello;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class BroadcasterController extends Controller
{
    public function index()
    {
        ClientHello::dispatch('test');
    }
}
