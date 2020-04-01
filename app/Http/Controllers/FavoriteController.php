<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;

class FavoriteController extends Controller
{
    public function add(Post $post)
    {
        $user = Auth::user();
        $isFavorite = $user->favorite_posts()->where('post_id', $post->id)->count();

        if ($isFavorite == 0) {
            $user->favorite_posts()->attach($post);
            return redirect()->back()->with('successMsg', 'Added!!!');
        } else {
            $user->favorite_posts()->detach($post);
            return redirect()->back()->with('successMsg', 'Removed!!!');
        }
    }
}
