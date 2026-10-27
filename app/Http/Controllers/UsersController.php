<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'message' => 'users',
            'results' => $users
        ], 200);
    }
}
