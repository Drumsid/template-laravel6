<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->get();
        return view('admin.subscriber.index', compact('subscribers'));
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber = Subscriber::findOrFail($subscriber->id);
        if ($subscriber) {
            $subscriber->delete();
        }

        return redirect(route('admin.subscriber.index'))->with('successMsg', 'Subscriber deleted!!!');
    }
}
