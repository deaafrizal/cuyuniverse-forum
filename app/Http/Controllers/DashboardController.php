<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SavedPosts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

final class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);

        if ($user->token == null) {
            $user->token = Str::random(60);
            $user->save();
            return back();
        }

        return Inertia::render('Dashboard/Index', [
            'title' => 'DASHBOARD',
        ]);
    }

    public function setting()
    {
        return Inertia::render('Dashboard/Setting', [
            'title' => 'USER PROFILE',
            'next' => 'DASHBOARD',
            'nextRoute' => 'dash.main',
        ]);
    }

    public function changePassword()
    {
        return Inertia::render('Dashboard/ChangePassword', [
            'title' => 'CHANGE PASSWORD',
            'next' => 'PROFILE',
            'nextRoute' => 'dash.setting.profile',
        ]);
    }

    public function notification()
    {
        $notifications = Auth::user()->unreadNotifications;

        return Inertia::render('Dashboard/Notification', [
            'notifications' => $notifications,
            'title' => 'NOTIFICATION',
            'next' => 'DASHBOARD',
            'nextRoute' => 'dash.main',
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications->find($id);

        $notification->markAsRead();
    }

    public function showSavedPost()
    {
        $savedPosts = SavedPosts::orderByDesc('id')->where('user_id', auth()->user()->id)->with('posts')->with('comments')->get();
        return Inertia::render('Dashboard/SavedPosts', [
            'data' => $savedPosts,
            'title' => 'SAVED POST',
            'page' => 'Postingan yang anda simpan',
            'next' => 'BUAT POSTINGAN',
            'nextRoute' => 'posts.main'
        ]);
    }

    public function update_photo(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:1048',
            'token' => 'required',
        ]);

        $users = new User();
        $user = $users->where('id', Auth::user()->id)->where('token', Auth::user()->token)->first();

        if ($request->hasFile('image')) {
            if (null !== $user->image) {
                Storage::delete('images/' . $user->image);
            }
            $fileName = Auth::user()->username . Str::random(60) . '.' . $request->image->getClientOriginalExtension();
            $filePath = $request->file('image')->storeAs('images', $fileName);
            $user->image = $fileName;
        }
        $user->save();

        return to_route('dash.setting.profile')->with('message', 'Avatar berhasil diganti');
    }

    public function update_username(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:40|unique:users|alpha_dash',
            'token' => 'required',
        ]);

        $users = User::find(Auth::user()->id)->where('token', $request->token);
        $users->update([
            'username' => $request->username,
        ]);

        return to_route('dash.main')->with('message', 'Username berhasil diganti');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|current_password:web',
            'newPassword' => 'required|min:8|confirmed|different:oldPassword',
            'token' => 'required',
        ]);

        $users = User::find(Auth::user()->id)->where('token', $request->token);
        $users->update([
            'password' => Hash::make($request->newPassword),
        ]);

        return to_route('dash.setting.profile')->with('message', 'Password berhasil diganti');
    }
}
