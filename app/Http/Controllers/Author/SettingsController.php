<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use App\User;

class SettingsController extends Controller
{
    public function index()
    {
        return view('author.settings');
    }

    public function updateProfile(Request $request)
    {
        // return dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'mimes:jpg,png,jpeg'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);
        $user = User::findOrFail(Auth::id());

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' . $image->getClientOriginalExtension();

            // create dir for profile image
            $this->dirExists('profile');

            // delete profile image
            $this->deleteImage('profile/', $user->image);

            // add profile image
            $userImage = Image::make($image)->resize(500, 500)->stream();
            Storage::disk('public')->put('profile/' . $imageName, $userImage);
        } else {
            $imageName = $user->image;
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imageName,
            'about' => isset($request->about) ? $request->about : $user->about
        ]);

        return redirect()->back()->with('successMsg', 'Profile updated!!!');
    }

    public function updatePassword(Request $request)
    {
        // return dd($request->all());
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hashedPass = Auth::user()->password;
        if (Hash::check($request->old_password, $hashedPass) && !Hash::check($request->password, $hashedPass)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            return redirect()->back()->with('successMsg', 'Good!');
        }

        return redirect()->back()->with('logginErrorMsg', 'Somfing wrong, try again!');
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
