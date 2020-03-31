<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use App\Category;
use Carbon\Carbon;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewAuthorPost;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::User()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:3|unique:posts',
            'image' => 'mimes:jpg,png,jpeg',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required',
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->title);

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' . $image->getClientOriginalExtension();

            // check post dir is exists
            $this->dirExists('post');

            $postImage = Image::make($image)->resize(1600, 1066)->stream();
            Storage::disk('public')->put('post/' . $imageName, $postImage);
        } else {
            $imageName = 'default.png';
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->image = $imageName;
        $post->slug = $slug;
        $post->body = $request->body;
        $post->status = isset($request->status) ? true : false;
        $post->is_approved = false;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        // оповещения на почту будут проблемы если, просто убрать этот блок
        $users = User::where('role_id', 1)->get();
        Notification::send($users, new NewAuthorPost($post));

        return redirect(route('author.post.index'))->with('successMsg', 'Post succesfull added!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return redirect()->back();
        }
        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return redirect()->back();
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit', compact('categories', 'tags', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return redirect()->back();
        }
        $this->validate($request, [
            'title' => 'required|min:3',
            'image' => 'mimes:jpg,png,jpeg',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required',
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->title);
        $status = isset($request->status) ? true : false;

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' . $image->getClientOriginalExtension();

            // check post dir is exists
            $this->dirExists('post');

            // delete category image
            $this->deleteImage('post/', $post->image);

            $postImage = Image::make($image)->resize(1600, 1066)->stream();
            Storage::disk('public')->put('post/' . $imageName, $postImage);
        } else {
            $imageName = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'image' => $imageName,
            'slug' => $slug,
            'status' => $status,
            'body' => $request->body
        ]);

        // удаляем все теги и записываем по новой
        $post->categories()->sync(request('categories'));

        $post->tags()->sync(request('tags'));

        return redirect(route('author.post.index'))->with('successMsg', 'Post updated!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return redirect()->back();
        }
        $post = Post::findOrFail($post->id);
        if ($post) {
            $post->categories()->detach();
            $post->tags()->detach();
            $post->delete();

            // delete post image
            $this->deleteImage('post/', $post->image);
        }

        return redirect(route('author.post.index'))->with('successMsg', 'Post was deleted!!!');
    }

    public function dirExists($dir)
    {
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    }

    public function deleteImage($dir, $img)
    {
        if (Storage::disk('public')->exists($dir . $img)) {
            Storage::disk('public')->delete($dir . $img);
        }
    }

    public function checkUserId(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return redirect()->back();
        }
    }
}
