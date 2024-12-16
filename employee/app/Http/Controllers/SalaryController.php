<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use App\Models\SalaryLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        // Get the list of salaries that have been calculated
        $salaries = Salary::with('user')->get();
        return view('admin.salary.index', compact('salaries'));
    }

    public function create()
    {
        // Get the list of employees and salary levels
        $users = User::all();
        return view('admin.salary.create', compact('users'));
    }


    public function calculateAll()
{
    $users = User::all();

    foreach ($users as $user) {
        $salaryLevel = $user->salaryLevel;
        if (!$salaryLevel) {
            continue; // Bỏ qua nếu không có salary level
        }

        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->startOfMonth()->addMonth();

        // Lấy danh sách điểm danh
        $attendances = $user->attendances()
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->get();

        $validDays = 0;
        $invalidDays = 0;
        $penaltyDays = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->status == 'valid') {
                if ($attendance->explanation) {
                    $penaltyDays++; // Ngày valid và có explanation => bị trừ 10%
                } else {
                    $validDays++;
                }
            } elseif ($attendance->status == 'invalid') {
                // Dù có explanation hay không, ngày invalid đều được tính
                $invalidDays++;
            }
        }

        // Tính lương
        $dailySalary = $salaryLevel->daily;
        $totalSalary = ($validDays * $dailySalary) + ($penaltyDays * $dailySalary * 0.9);

        // Kiểm tra nếu bản ghi lương đã tồn tại
        $existingSalary = Salary::where('user_id', $user->id)
            ->where('month', $currentMonth->format('Y-m'))
            ->first();

        if ($existingSalary) {
            // **Cập nhật bản ghi lương đã tồn tại**
            $existingSalary->update([
                'valid_days' => $validDays + $penaltyDays,
                'invalid_days' => $invalidDays,
                'salary' => $totalSalary,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);
        } else {
            // **Tạo mới nếu chưa tồn tại**
            Salary::create([
                'user_id' => $user->id,
                'month' => $currentMonth->format('Y-m'),
                'valid_days' => $validDays + $penaltyDays,
                'invalid_days' => $invalidDays,
                'salary' => $totalSalary,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);
        }
    }

    return redirect()->route('salary.index')->with('success', 'Salaries have been calculated and updated for all employees!');
}




public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $user = User::with('salaryLevel')->findOrFail($request->user_id);
    $salaryLevel = $user->salaryLevel;

    if (!$salaryLevel) {
        return redirect()->back()->withErrors(['error' => 'The employee does not have a salary level. Please set the salary level before calculating salary.']);
    }

    $currentMonth = now()->startOfMonth();
    $nextMonth = now()->startOfMonth()->addMonth();

    // Lấy danh sách điểm danh
    $attendances = $user->attendances()
        ->whereBetween('date', [$currentMonth, $nextMonth])
        ->get();

    $validDays = 0;
    $invalidDays = 0;
    $penaltyDays = 0;

    foreach ($attendances as $attendance) {
        if ($attendance->status == 'valid') {
            if ($attendance->explanation) {
                $penaltyDays++; // Ngày valid và có explanation => bị trừ 10%
            } else {
                $validDays++;
            }
        } elseif ($attendance->status == 'invalid') {
            // Dù có explanation hay không, ngày invalid đều được tính
            $invalidDays++;
        }
    }

    // Tính lương
    $dailySalary = $salaryLevel->daily;
    $totalSalary = ($validDays * $dailySalary) + ($penaltyDays * $dailySalary * 0.9);

    // Kiểm tra nếu đã tồn tại bản ghi lương
    $existingSalary = Salary::where('user_id', $request->user_id)
        ->where('month', $currentMonth->format('Y-m'))
        ->first();

    if ($existingSalary) {
        // Cập nhật bản ghi lương nếu đã tồn tại
        $existingSalary->update([
            'valid_days' => $validDays + $penaltyDays,
            'invalid_days' => $invalidDays,
            'salary' => $totalSalary,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('salary.index')->with('success', 'The salary has been updated successfully!');
    }

    // Tạo mới nếu chưa tồn tại
    Salary::create([
        'user_id' => $request->user_id,
        'month' => $currentMonth->format('Y-m'),
        'valid_days' => $validDays + $penaltyDays,
        'invalid_days' => $invalidDays,
        'salary' => $totalSalary,
        'processed_by' => Auth::id(),
        'processed_at' => now(),
    ]);

    return redirect()->route('salary.index')->with('success', 'The salary has been calculated and saved successfully!');
}



public function getAttendanceDays($userId)
{
    // Lấy thông tin nhân viên
    $user = User::with('attendances')->findOrFail($userId);

    // Xác định tháng hiện tại
    $currentMonth = now()->startOfMonth();
    $nextMonth = now()->startOfMonth()->addMonth();

    // Tính số ngày hợp lệ và không hợp lệ trong tháng hiện tại
    $validDays = $user->attendances()
        ->whereBetween('date', [$currentMonth, $nextMonth])
        ->where('status', 'valid')
        ->count();

    $invalidDays = $user->attendances()
        ->whereBetween('date', [$currentMonth, $nextMonth])
        ->where('status', 'invalid')
        ->count();

    // Trả về dữ liệu dưới dạng JSON
    return response()->json([
        'valid_days' => $validDays,
        'invalid_days' => $invalidDays,
    ]);
}



 }
