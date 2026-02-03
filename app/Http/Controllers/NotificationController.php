<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Fetch notifications for the authenticated user
    public function index()
    {
        $notifications = Notifications::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        return view('notifications.index', compact('notifications'));
    }

    // Mark a notification as read
    public function markAsRead($id)
    {
        $notification = Notifications::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $notification->markAsRead();
        return redirect()->back();
    }
}
