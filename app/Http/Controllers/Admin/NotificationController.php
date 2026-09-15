<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'notifications' => $admin->notifications()->latest()->take(10)->get(),
            'unread_count' => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead($id)
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.',
            'unread_count' => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead()
    {
        $admin = Auth::guard('admin')->user();
        $admin->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read.',
            'unread_count' => $admin->unreadNotifications()->count(),
            'notifications' => $admin->notifications()->latest()->take(10)->get(),
        ]);
    }
}
