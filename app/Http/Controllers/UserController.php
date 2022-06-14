<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $users = User::paginate(10);

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Display the resource
     */
    public function show(int $id)
    {
        $user = User::find($id);

        if ($user == null)
        {
            return response()->redirectToRoute('admin.users.index');
        }

        return view('users.show', [
            'user' => $user
        ]);
    }
}
