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

        $entry   = UserAttendance::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
        $entries = UserAttendance::where('user_id', auth()->id())->get();
        return view('guest.dashboard', compact('entry', 'entries'));
    }
}
