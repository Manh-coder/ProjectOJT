<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAttendance;
use App\Models\UserNotificationSchedule;
use Illuminate\Http\Request;

use App\Mail\ScheduleUpdated;
use Illuminate\Support\Facades\Mail;

class EmailScheduleController extends Controller
{
    public function index()
    {
        $schedule = UserAttendance::first();
        return view('admin.email-schedule', compact('schedule'));
    }

    public function update(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'check_in_time'  => 'nullable', // Cho phép giá trị null
            'check_out_time' => 'nullable', // Cho phép giá trị null
        ]);

        // Chỉ cập nhật khi giá trị không phải null
        $data = [];
        if ($request->filled('check_in_time')) {
            $data['check_in_time'] = $request->check_in_time;
        }
        if ($request->filled('check_out_time')) {
            $data['check_out_time'] = $request->check_out_time;
        }

        if (!empty($data)) {
            User::where('type', 2)->get()->each(function ($user) use ($data) {
                UserNotificationSchedule::updateOrCreate(
                    ['user_id' => $user->id],
                    $data
                );


                // Gửi email thông báo
            $emailData = array_merge($data, ['user_name' => $user->name]);
            Mail::to($user->email)->send(new ScheduleUpdated($emailData));
            });
            
            return redirect()->back()->with('success', 'Email schedule updated successfully!');
        }

        return redirect()->back()->with('error', 'No changes made.');
    }

}

