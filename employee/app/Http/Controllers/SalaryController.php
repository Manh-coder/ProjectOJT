<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Salary;
use App\Models\User;
use App\Models\SalaryLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        // Get the list of salaries that have been calculated
        $salaries = Salary::with('user')->get();
        $users    = User::where('type', 2)->has('salaries')->get();
        return view('admin.salary.index', compact('salaries', 'users'));
    }

    public function create()
    {
        // Get the list of employees and salary levels
        $users = User::all();
        return view('admin.salary.create', compact('users'));
    }


    public function calculateAll(Request $request)
    {
        $users = User::all();

        foreach ($users as $user) {
            $salaryLevel = $user->salaryLevel;
            if (!$salaryLevel) {
                continue; // Bỏ qua nếu không có salary level
            }
            $leaveBalance = $user->leaveBalance;
            if (!$leaveBalance) {
                continue; // Bỏ qua nếu không có salary level
            }

            $currentMonth = now()->startOfMonth();
            $nextMonth    = now()->startOfMonth()->addMonth();
            if ($request->input('month')) {
                $currentMonth = Carbon::parse(date("Y-{$request->input('month')}-01"));
                $nextMonth    = Carbon::parse(date("Y-{$request->input('month')}-01"))->addMonth();
            }

            // Lấy danh sách điểm danh
            $attendances = $user->attendances()
                ->whereBetween('date', [$currentMonth, $nextMonth])
                ->get();

            // Lấy danh sách nghỉ phép 
            $leaveRequests = LeaveRequest::query()
                ->where('user_id', $user->id)
                ->get();

            $validDays   = 0;
            $invalidDays = 0;
            $penaltyDays = 0;

            $leaveValid = 0;
            $leaveTotal = $leaveBalance->total_leave_days;

            foreach ($attendances as $attendance) {
                // nếu là ngày cuối tuần thì bỏ qua
                if (Carbon::parse($attendance->date)->isWeekend()) {
                    continue;
                }

                // Nếu thuộc diện nghỉ phép thì đuộc tính lương luôn
                if (
                    $leaveRequests
                        ->where('start_date', '>=', $attendance->date)
                        ->where('end_date', '<=', $attendance->date)
                        ->where('status', 'approved')
                        ->isNotEmpty()
                ) {
                    /**
                     * Tăng ngày nghỉ phép
                     * - Nếu số ngày nghỉ lớn hơn tổng thì quy là không hợp lệ
                     */
                    $leaveValid++;
                    if ($leaveValid <= $leaveTotal) {
                        $validDays++;
                        continue;
                    } else {
                        $invalidDays++;
                        continue;
                    }
                }

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
            // Nếu là nhân viên chính thức
            if ($user->work_year) {
                $dailySalary = $salaryLevel->monthly;
            }
            $totalSalary = ($validDays * $dailySalary) + ($penaltyDays * $dailySalary * 0.9);

            // Kiểm tra nếu bản ghi lương đã tồn tại
            $existingSalary = Salary::where('user_id', $user->id)
                ->where('month', $currentMonth->format('Y-m'))
                ->first();

            if ($existingSalary) {
                // **Cập nhật bản ghi lương đã tồn tại**
                $existingSalary->update([
                    'valid_days'   => $validDays + $penaltyDays,
                    'invalid_days' => $invalidDays,
                    'salary'       => $totalSalary,
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);
            } else {
                // **Tạo mới nếu chưa tồn tại**
                Salary::create([
                    'user_id'      => $user->id,
                    'month'        => $currentMonth->format('Y-m'),
                    'valid_days'   => $validDays + $penaltyDays,
                    'invalid_days' => $invalidDays,
                    'salary'       => $totalSalary,
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

        $user        = User::with('salaryLevel')->findOrFail($request->user_id);
        $salaryLevel = $user->salaryLevel;

        if (!$salaryLevel) {
            return redirect()->back()->withErrors(['error' => 'The employee does not have a salary level. Please set the salary level before calculating salary.']);
        }
        if (!$user->leaveBalance) {
            return redirect()->back()->withErrors(['error' => 'The employee does not have a salary balance. Please set the salary level before calculating balance.']);
        }

        $currentMonth = now()->startOfMonth();
        $nextMonth    = now()->startOfMonth()->addMonth();
        if ($request->input('month')) {
            $currentMonth = Carbon::parse(date("Y-{$request->input('month')}-01"));
            $nextMonth    = Carbon::parse(date("Y-{$request->input('month')}-01"))->addMonth();
        }
        // Lấy danh sách điểm danh
        $attendances = $user->attendances()
            ->whereBetween('date', [$currentMonth->format('Y-m-d'), $nextMonth->format('Y-m-d')])
            ->get();

        // Lấy danh sách nghỉ phép 
        $leaveRequests = LeaveRequest::query()
            ->where('user_id', $user->id)
            ->get();


        $validDays   = 0;
        $invalidDays = 0;
        $penaltyDays = 0;

        $leaveValid = 0;
        $leaveTotal = $user->leaveBalance->total_leave_days;

        foreach ($attendances as $attendance) {

            // nếu là ngày cuối tuần thì bỏ qua
            if (Carbon::parse($attendance->date)->isWeekend()) {
                continue;
            }

            // Nếu thuộc diện nghỉ phép thì đuộc tính lương luôn
            if (
                $leaveRequests
                    ->where('start_date', '>=', $attendance->date)
                    ->where('end_date', '<=', $attendance->date)
                    ->where('status', 'approved')
                    ->isNotEmpty()

            ) {
                /**
                 * Tăng ngày nghỉ phép
                 * - Nếu số ngày nghỉ lớn hơn tổng thì quy là không hợp lệ
                 */
                $leaveValid++;
                if ($leaveValid <= $leaveTotal) {
                    $validDays++;
                    continue;
                } else {
                    $invalidDays++;
                    continue;
                }

            }

            if ($attendance->status == 'valid') {
                if ($attendance->explanation) {
                    $penaltyDays++; // Ngày valid và có explanation => bị trừ 10%
                } else {
                    $validDays++;
                }
            } elseif ($attendance->status == 'invalid') {
                // Dù có explanation hay không, ngày invalid không đều được tính
                $invalidDays++;
            }
        }

        // Tính lương
        $dailySalary = $salaryLevel->daily;
        // Nếu là nhân viên chính thức
        if ($user->work_year) {
            $dailySalary = $salaryLevel->monthly;
        }

        $totalSalary = ($validDays * $dailySalary) + ($penaltyDays * $dailySalary * 0.9);

        // Kiểm tra nếu đã tồn tại bản ghi lương
        $existingSalary = Salary::where('user_id', $request->user_id)
            ->where('month', $currentMonth->format('Y-m'))
            ->first();

        if ($existingSalary) {
            // Cập nhật bản ghi lương nếu đã tồn tại
            $existingSalary->update([
                'valid_days'   => $validDays + $penaltyDays,
                'invalid_days' => $invalidDays,
                'salary'       => $totalSalary,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            return redirect()->route('salary.index')->with('success', 'The salary has been updated successfully!');
        }

        // Tạo mới nếu chưa tồn tại
        Salary::create([
            'user_id'      => $request->user_id,
            'month'        => $currentMonth->format('Y-m'),
            'valid_days'   => $validDays + $penaltyDays,
            'invalid_days' => $invalidDays,
            'salary'       => $totalSalary,
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
        $nextMonth    = now()->startOfMonth()->addMonth();

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
            'valid_days'   => $validDays,
            'invalid_days' => $invalidDays,
        ]);
    }



}
