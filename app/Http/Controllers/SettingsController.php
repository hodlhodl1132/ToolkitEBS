<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard
     */
    public function index()
    {
        return view('streamer.index', [
            'user' => Auth::user()
        ]);
    }
}
