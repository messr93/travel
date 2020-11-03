<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\RespondBack;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{

    use RespondBack;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markAsRead(Request $request){
        $notification = Notification::where('id', $request->id)->first();
        if(!isset($notification))
            return $this->ResponseFail('This notification not found');
        if($request->user()->id !== $notification->	notifiable_id)              //check if this user is the owner
            return $this->ResponseFail('this notification not belongs to you');
        $notification->update(['read_at' => now()]);

        Notification::where('created_at', '<', now()->subMonth()->toDateTimeString())->delete();       /// delete old notification (older than month)
        return $this->ResponseSuccessMessage('notification marked as read');
    }
}
