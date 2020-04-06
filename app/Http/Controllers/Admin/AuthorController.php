<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = User::author()
            ->withCount('posts')
            ->withCount('favorite_posts')
            ->withCount('comments')
            ->get();
        // return dd($authors);
        return view('admin.authors', compact('authors'));;
    }

    public function destroy(User $author)
    {
        $user = User::findOrFail($author->id);
        if ($user) {
            $user->delete();
        }

        return redirect(route('admin.author.index'))->with('successMsg', 'User deleted!!!');
    }
}
