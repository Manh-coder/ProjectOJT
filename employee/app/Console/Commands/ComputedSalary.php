<?php

namespace App\Console\Commands;

use App\Models\LeaveRequest;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ComputedSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:computed-salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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


            // Lấy danh sách điểm danh
            $attendance = $user->attendances()
                ->where('date', date('Y-m-d'))
                ->first();
            if (!$attendance)
                continue;

            // Lấy danh sách nghỉ phép 
            $leaveRequests = LeaveRequest::query()
                ->where('user_id', $user->id)
                ->get();

            $validDays   = 0;
            $invalidDays = 0;
            $penaltyDays = 0;

            $leaveValid = 0;
            $leaveTotal = $leaveBalance->total_leave_days;


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

            // Tính lương
            $dailySalary = $salaryLevel->daily;
            // Nếu là nhân viên chính thức
            if ($user->work_year) {
                $dailySalary = $salaryLevel->monthly;
            }
            $totalSalary = ($validDays * $dailySalary) + ($penaltyDays * $dailySalary * 0.9);

            // Kiểm tra nếu bản ghi lương đã tồn tại
            $existingSalary = Salary::where('user_id', $user->id)
                ->where('month', date('Y-m'))
                ->first();

            if ($existingSalary) {
                // **Cập nhật bản ghi lương đã tồn tại**
                $existingSalary->update([
                    'valid_days'   => $validDays + $penaltyDays,
                    'invalid_days' => $invalidDays,
                    'salary'       => $totalSalary,
                    'processed_by' => null,
                    'processed_at' => now(),
                ]);
            } else {
                // **Tạo mới nếu chưa tồn tại**
                Salary::create([
                    'user_id'      => $user->id,
                    'month'        => date('Y-m'),
                    'valid_days'   => $validDays + $penaltyDays,
                    'invalid_days' => $invalidDays,
                    'salary'       => $totalSalary,
                    'processed_by' => null,
                    'processed_at' => now(),
                ]);
            }
        }

    }
}
