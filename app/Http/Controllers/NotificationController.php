<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $chatId = $request->input('chat_id');

        if (!$chatId || !$user) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $notifications = Notification::where('chat_id', $chatId)
            ->whereHas('recipients', fn($q) => $q->where('user_id', $user->id))
            ->get();

        foreach ($notifications as $notification) {
            $notification->recipients()->updateExistingPivot($user->id, ['is_read' => 1]);
        }

        return response()->json(['success' => true]);
    }
}
