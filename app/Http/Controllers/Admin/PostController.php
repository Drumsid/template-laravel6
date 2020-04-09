<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use App\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AuthorPostAprroved;
use App\Subscriber;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPostNotify;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
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
        return view('admin.post.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return dd($request->all());
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
        $post->is_approved = true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        // оповещения на почту будут проблемы если, просто убрать этот блок
        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber) {
            Notification::route('mail', $subscriber->email)
                ->notify(new NewPostNotify($post));
        }

        return redirect(route('admin.post.index'))->with('successMsg', 'Post succesfull added!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('categories', 'tags', 'post'));
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
        // $post->categories()->detach();
        $post->categories()->sync(request('categories'));

        // $post->tags()->detach();
        $post->tags()->sync(request('tags'));

        return redirect(route('admin.post.index'))->with('successMsg', 'Post updated!!!');
    }

    public function pending()
    {
        $posts = Post::where('is_approved', false)->get();
        return view('admin.post.pending', compact('posts'));
    }

    public function approval(Post $post)
    {
        $post = Post::findOrFail($post->id);
        $post->update([
            'is_approved' => true
        ]);

        // оповещения на почту будут проблемы если, просто убрать этот блок
        // $post->user->notify(new AuthorPostAprroved($post));

        // $subscribers = Subscriber::all();
        // foreach ($subscribers as $subscriber) {
        //     Notification::route('mail', $subscriber->email)
        //         ->notify(new NewPostNotify($post));
        // }

        return redirect(route('admin.post.pending'))->with('successMsg', 'Post approved!!!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post = Post::findOrFail($post->id);
        if ($post) {
            $post->categories()->detach();
            $post->tags()->detach();
            $post->delete();

            // delete post image
            $this->deleteImage('post/', $post->image);
        }

        return redirect(route('admin.post.index'))->with('successMsg', 'Post was deleted!!!');
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
}
