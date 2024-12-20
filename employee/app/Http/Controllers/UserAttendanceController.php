<?php

namespace App\Http\Controllers;

use App\Models\UserAttendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class UserAttendanceController extends Controller
{
    // Hiển thị danh sách chấm công
    // public function index()
    // {
    //     $attendances = UserAttendance::paginate(2);
    //     return view('user_attendance.index', compact('attendances'));
    // }


    public function index()
    {
        $attendances = UserAttendance::from(
            function ($query) {
                $query
                    ->from('user_attendance')
                    ->selectRaw('user_id, MAX(datetime_ci) AS max_time')
                    ->groupBy('user_id');
            },
            'latest_attendance'
        )
            ->join('user_attendance', function ($join) {
                $join->on('user_attendance.user_id', '=', 'latest_attendance.user_id')
                    ->on('user_attendance.datetime_ci', '=', 'latest_attendance.max_time');
            })
            ->with('user')  // Tải quan hệ với bảng 'user'
            ->select('user_attendance.*')  // Chọn các trường cần thiết
            ->paginate(10);
        return view('user_attendance.index', compact('attendances'));
    }






    // Hiển thị form tạo mới chấm công
    public function create()
    {
        $employees = User::typeEmployee()->get();
        return view('user_attendance.create', compact('employees'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'time'    => 'required|date',
            'type'    => 'required|in:in,out',
        ]);

        // Tạo một bản ghi mới mỗi khi có sự kiện chấm công
        UserAttendance::create([
            'user_id'    => $request->user_id,
            'time'       => $request->time,
            'type'       => $request->type,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('user-attendance.index')->with('success', 'User-attendance recorded successfully.');
    }



    public function edit($id)
    {
        $attendance = UserAttendance::findOrFail($id);
        $employees  = Employee::all();

        // Định dạng `time` để phù hợp với `datetime-local`
        $attendance->time = Carbon::parse($attendance->time)->format('Y-m-d\TH:i');

        return view('user_attendance.edit', compact('attendance', 'employees'));
    }



    // Cập nhật chấm công
    public function update(Request $request, $id)
    {
        $attendance = UserAttendance::findOrFail($id);

        $request->validate([
            'user_id' => 'required|integer',
            'time'    => 'required|date',
            'type'    => 'required|in:in,out',
        ]);

        // Cập nhật thông tin updated_by
        $attendanceData               = $request->all();
        $attendanceData['updated_by'] = auth()->id();

        $attendance->update($attendanceData);
        return redirect()->route('user-attendance.index')->with('success', 'Update User-attendance successfully.');
    }


    // Xóa chấm công
    public function destroy($id)
    {
        $attendance = UserAttendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('user-attendance.index')->with('success', 'Delete User-attendance successfully.');
    }

    // Tìm kiếm chấm công
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword) {
            $attendances = UserAttendance::whereHas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
                ->orWhere('type', 'like', "%$keyword%")
                ->paginate(2); // Thay get() bằng paginate() để hỗ trợ phân trang
        } else {
            $attendances = UserAttendance::paginate(2);
        }

        return view('user_attendance.index', compact('attendances', 'keyword'));
    }







    public function show($userId)
    {
        // Tìm thời gian Check In đầu tiên
        $entries = UserAttendance::where('user_id', $userId)->get();
        return view('user_attendance.details', compact('entries'));
    }





}
