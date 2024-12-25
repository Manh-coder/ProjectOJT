<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationScheduleController extends Controller
{
    public function index()
    {
        $schedule = UserNotificationSchedule::firstOrCreate(
            ['user_id' => Auth::id()],
            ['check_in_time' => null, 'check_out_time' => null]
        );

        return view('guest.notification_schedule', compact('schedule'));

        

    }

    public function update(Request $request)
    {
        $request->validate([
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
        ]);

        $schedule = UserNotificationSchedule::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'check_in_time' => $request->check_in_time,
                'check_out_time' => $request->check_out_time,
            ]
        );

        return redirect()->back()->with('success', 'Notification schedule updated successfully!');
    }
}
