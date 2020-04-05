<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $posts = Post::where('title', 'LIKE', "%$query%")
            ->orWhere('body', 'LIKE', "%$query%")->approved()->published()->get();

        if ($query == null) {
            $posts = [];

            return view('search', compact('query', 'posts'));
        }

        return view('search', compact('query', 'posts'));
    }
}
