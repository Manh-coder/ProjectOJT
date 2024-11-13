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
        // Lấy bản ghi chấm công cuối cùng của mỗi nhân viên với phân trang
        // $attendances = UserAttendance::with('user')
        //     ->select('user_attendance.*')
        //     ->join(
        //         DB::raw('(SELECT user_id, MAX(time) as max_time FROM user_attendance GROUP BY user_id) as latest_attendance'),
        //         function ($join) {
        //             $join->on('user_attendance.user_id', '=', 'latest_attendance.user_id')
        //                 ->on('user_attendance.time', '=', 'latest_attendance.max_time');
        //         }
        //     )
        //     ->paginate(10); // Sử dụng phân trang

        // Tính toán `total_duration` cho mỗi bản ghi
        // foreach ($attendances as $attendance) {
        //     $firstCheckIn = UserAttendance::where('user_id', $attendance->user_id)
        //         ->where('type', 'in')
        //         ->orderBy('time', 'asc')
        //         ->first();

        //     $lastCheckOut = UserAttendance::where('user_id', $attendance->user_id)
        //         ->where('type', 'out')
        //         ->orderBy('time', 'desc')
        //         ->first();

        //     if ($firstCheckIn && $lastCheckOut) {
        //         $firstCheckInTime           = Carbon::parse($firstCheckIn->time);
        //         $lastCheckOutTime           = Carbon::parse($lastCheckOut->time);
        //         $attendance->total_duration = $lastCheckOutTime->diffInHours($firstCheckInTime) . ' hours';
        //     } else {
        //         $attendance->total_duration = 'N/A';
        //     }
        // }
        // 
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

    // Lưu chấm công mới
//     public function store(Request $request)
// {
//     $request->validate([
//         'user_id' => 'required|integer',
//         'time' => 'required|date',
//         'type' => 'required|in:in,out',
//     ]);

    //     // Thêm thông tin created_by
//     $attendanceData = $request->all();
//     $attendanceData['created_by'] = auth()->id();
//     $attendanceData['updated_by'] = auth()->id();

    //     UserAttendance::create($attendanceData);
//     return redirect()->route('user-attendance.index')->with('success', 'New User-attendance successfully.');
// }


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


    // Hiển thị form chỉnh sửa chấm công
    // public function edit($id)
    // {
    //     $attendance = UserAttendance::findOrFail($id);
    //     $employees = Employee::all();
    //     return view('user_attendance.edit', compact('attendance', 'employees'));
    // }


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




    // public function show($userId)
// {
//     // Lấy dữ liệu "Check In" và "Check Out" của nhân viên theo user_id
//     $checkIn = UserAttendance::where('user_id', $userId)->where('type', 'in')->orderBy('time', 'asc')->first();
//     $checkOut = UserAttendance::where('user_id', $userId)->where('type', 'out')->orderBy('time', 'desc')->first();

    //     // Trả về view với dữ liệu "Check In" và "Check Out"
//     return view('user_attendance.details', compact('checkIn', 'checkOut'));
// }










    public function show($userId)
    {
        // Tìm thời gian Check In đầu tiên
        $entries = UserAttendance::where('user_id', $userId)->get();




        return view('user_attendance.details', compact('entries'));
    }





}
