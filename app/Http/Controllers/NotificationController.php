<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        if(!Auth::check()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        // Assuming you have a Notification model and a user relationship
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc') // Order by latest first
            ->get();    
        // Mark notifications as read
       $user->unreadNotifications->markAsRead();

        return response()->json($notifications);
    }
}
