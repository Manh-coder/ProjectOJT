<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use App\Models\UserAttendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy số lượng nhân viên v                                                                                                                                                                     à phòng ban
        $employeeCount   = User::count();
        $departmentCount = Department::where('status', 1)->count();

        // Lấy danh sách các nhân viên mới nhất
        $recentEmployees = User::latest()->take(3)->get();


        // Lấy danh sách các phòng ban đang hoạt động
        $activeDepartments = Department::where('status', 1)->get();

        return view('dashboard', compact('employeeCount', 'departmentCount', 'recentEmployees', 'activeDepartments'));
    }


    public function indexGuest()
{
    // Lấy thông tin chấm công của người dùng hôm nay
    $entry = UserAttendance::where('user_id', auth()->id())
        ->where('date', date('Y-m-d'))
        ->first();

    // Lấy tất cả dữ liệu chấm công của người dùng, sắp xếp theo ngày từ mới nhất đến cũ nhất và phân trang
    $entries = UserAttendance::where('user_id', auth()->id())
        ->orderBy('date', 'desc')  // Sắp xếp theo ngày mới nhất
        ->paginate(2);  // Phân trang 2 bản ghi mỗi trang

    // Trả về view với dữ liệu
    return view('guest.dashboard', compact('entry', 'entries'));
}

}
