<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $comments = $user->comments()->latest()->get();
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
