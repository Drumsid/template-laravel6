<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthorController extends Controller
{
    public function profile($username)
    {
        $user = User::where('username', $username)->first();
        $posts = $user->posts()->approved()->published()->paginate(4);
        return view('profile.author', compact('user', 'posts'));;
    }
}
