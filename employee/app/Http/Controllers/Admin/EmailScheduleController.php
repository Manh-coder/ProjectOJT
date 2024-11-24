<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAttendance;
use Illuminate\Http\Request;

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
        'check_in_time' => 'nullable|date_format:H:i', // Cho phép giá trị null
        'check_out_time' => 'nullable|date_format:H:i', // Cho phép giá trị null
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
        // Cập nhật tất cả các bản ghi trong bảng user_attendance
        UserAttendance::query()->update($data);

        return redirect()->back()->with('success', 'Email schedule updated successfully!');
    }

    return redirect()->back()->with('error', 'No changes made.');
}

}

