<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index(Request $request)
    {
        return response()->json([
            'notifications' => Notification::where('user_id', $request->user()->id)->latest()->get()
        ]);
    }

    public function update(Notification $notification)
    {
        // dd('bruh');
        $notification->update([
            // 'is_read' => !$notification->is_read,
            'is_clicked' => !$notification->is_clicked
        ]);

        return back();

    }

    public function destroy(Notification $notification, Request $request)
    {
        // dd($notification);
        $notification->delete();

        return back();
    }

    public function read_notifications()
    {
        Notification::where('is_read', false)
        ->where('user_id', auth()->id())
        ->update([
            'is_read' => true
        ]);

        return response()->json(200);
    }

    public function click(Notification $notification)
    {
        $h = $notification->update([
            // 'is_read' => !$notification->is_read,
            'is_clicked' => true
        ]);
        return back();
    }

}
