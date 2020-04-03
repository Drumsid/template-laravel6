<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->get();
        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $commentDelete = Comment::findOrFail($comment->id);
        if ($commentDelete) {
            $commentDelete->delete();
        }

        return redirect(route('admin.comments.index'))->with('successMsg', 'Comment deleted!!!');
    }
}
