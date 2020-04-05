<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\Session;
use App\Category;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->approved()->published()->paginate(6);
        return view('posts', compact('posts'));
    }

    public function details($slug)
    {
        $post = Post::approved()->published()->where('slug', $slug)->first();

        if ($post == null) {
            return redirect()->back();
        }
        $blogKey = 'blog_' . $post->id;
        if (!Session::has($blogKey)) {
            $post->increment('view_count');
            Session::put($blogKey, 1);
        }
        // $randomPosts = Post::all()->random(3);
        // $randomPosts = Post::all()->where('id', '!=', $post->id)->random(3);
        $randomPosts = Post::approved()->published()->where('id', '!=', $post->id)->take(3)->inRandomOrder()->get();
        $tags = Tag::all();
        return view('post', compact('post', 'randomPosts', 'tags'));
    }

    public function postByCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $posts = $category->posts()->approved()->published()->get();
        return view('category.posts', compact('category', 'posts'));
    }

    public function postByTag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        $posts = $tag->posts()->approved()->published()->get();
        return view('tag.posts', compact('tag', 'posts'));
    }
}
