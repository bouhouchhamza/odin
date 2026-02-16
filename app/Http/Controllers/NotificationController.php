<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, DatabaseNotification $notification): RedirectResponse|JsonResponse
    {
        abort_if($notification->notifiable_id !== $request->user()->id, 403);

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Notification marked as read']);
        }

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'All notifications marked as read']);
        }

        return back();
    }
}
