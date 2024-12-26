<?php

namespace App\Http\Controllers;


use App\Mail\AttendanceStatusChanged;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use App\Models\LeaveBalance;
use App\Models\SalaryLevel;
use App\Models\User;
use App\Models\UserAttendance;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    // Hiển thị danh sách nhân viên
    // Sửa phương thức index() trong EmployeeController
    public function index(Request $request)
    {
        $keyword   = $request->get('keyword');
        $entries   = UserAttendance::paginate(2);
        $employees = User::query()
            ->when(
                $keyword,
                fn($q) => $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
            )
            ->typeEmployee()

            ->paginate(4); // Số lượng nhân viên hiển thị trên mỗi trang

        return view('employees.index', compact('employees'));
    }


    // Hiển thị form tạo mới nhân viên
    public function create()
    {
        $departments = Department::all();
        $levels      = SalaryLevel::all();
        return view('employees.create', compact('departments', 'levels'));
    }

    // Lưu nhân viên mới
    public function store(Request $request)
    {
        $employeeData = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'department_id'   => 'required|integer',
            'phone_number'    => ['required', 'regex:/^((\+84)|0)(\d{9,10})$/'], // Validate số điện thoại Việt Nam
            'username'        => ['required', 'unique:users,username'],
            'password'        => ['required'],
            'salary_level_id' => ['required'],
            'position'        => ['required'],
            'gender'          => ['required'],
            'age'             => ['required'],
        ]);

        // Thêm thông tin created_by
        $employeeData['created_by'] = auth()->id();
        $employeeData['updated_by'] = auth()->id();
        $employeeData['password']   = Hash::make($request->input('password'));

        $entry = User::create([
            ...$employeeData,
            'type' => User::TYPE_OPTIONS['employee']
        ]);
        LeaveBalance::create([
            'user_id'          => $entry->id,
            'total_leave_days' => 8
        ]);
        return redirect()->route('employees.index')->with('success', 'New Employee created successfully.');
    }



    // Hiển thị form chỉnh sửa thông tin nhân viên
    public function edit($id)
    {
        $employee    = User::findOrFail($id);
        $departments = Department::all();
        $levels      = SalaryLevel::all();
        return view('employees.edit', compact('employee', 'departments', 'levels'));
    }

    public function update(Request $request, $id)
    {

        $employee = User::findOrFail($id);

        $employeeData = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $id,
            'department_id'   => 'required|integer',
            'phone_number'    => ['required', 'regex:/^((\+84)|0)(\d{9,10})$/'], // Validate số điện thoại Việt Nam
            'username'        => ['required', 'unique:users,username,' . $id],
            // 'password'        => ['required'],
            'salary_level_id' => ['required'],
            'position'        => ['required'],
            'gender'          => ['required'],
            'age'             => ['required'],
        ]);

        // Cập nhật thông tin updated_by
        $employeeData['updated_by'] = auth()->id();
        if ($request->input('password')) {
            $employeeData['password'] = Hash::make($request->input('password'));
        }
        $employee->update($employeeData);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }



    // Xóa nhân viên
    public function destroy($id)
    {
        try {
            $employee = User::findOrFail($id);
            $employee->delete();
            UserAttendance::where('user_id', $employee->id)->delete();

            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra nếu lỗi liên quan đến khóa ngoại
            if ($e->getCode() == 23000) {
                return back()->with('error', 'Cannot delete employee because there are related attendance records.');
            }
            // Xử lý các ngoại lệ khác (nếu có)
            return back()->with('error', 'An error occurred while deleting the employee.');
        }
    }


    // Tìm kiếm nhân viên
    // Sửa phương thức search() để hỗ trợ phân trang
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        $employees = User::query()
            ->when(
                $keyword,
                fn($q) => $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
            )
            ->paginate(4); // Số lượng nhân viên hiển thị trên mỗi trang

        return view('employees.index', compact('employees'));
    }




    public function show($id)
    {
        $employee = User::findOrFail($id);
        return view('employees.show', compact('employee'));
    }



    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
            return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import employees. There is duplicate data');
        }
    }

    public function export()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }



    public function exportWithPhpSpreadsheet(Request $request)
    {
        // Tạo file Excel
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheetIndex = 0;
        $page       = 1;
        do {
            // Lấy dữ liệu từ database và điền vào các hàng
            $employees = User::query()
                ->typeEmployee()
                ->paginate($request->integer('per_page') ? $request->integer('per_page') : 1, page: $page);

            // 
            $sheet = $spreadsheet->createSheet($sheetIndex);
            $spreadsheet->setActiveSheetIndex($sheetIndex);

            // 
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Phone');
            $sheet->setCellValue('E1', 'Department ID');
            $sheet->setCellValue('F1', 'Position');
            $sheet->setCellValue('G1', 'Age');
            $sheet->setCellValue('H1', 'Gender');
            // 
            $row = 2;

            foreach ($employees as $employee) {
                $sheet->setCellValue('A' . $row, $employee->id);
                $sheet->setCellValue('B' . $row, $employee->name);
                $sheet->setCellValue('C' . $row, $employee->email);
                $sheet->setCellValue('D' . $row, $employee->phone);
                $sheet->setCellValue('E' . $row, $employee->department_id);
                $sheet->setCellValue('F' . $row, $employee->position);
                $sheet->setCellValue('G' . $row, $employee->age);
                $sheet->setCellValue('H' . $row, $employee->gender);
                $row++;
            }

            $sheetIndex++;
            $page++;
        } while ($employees->hasMorePages());

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path() . '/' . 'employees.xlsx');
        // return Response::download(storage_path() . '/' . 'employees.xlsx');
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . 'employees.xlsx' . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function action(Request $request)
    {
        $type = $request->input('action');

        // Tìm bản ghi hiện tại của nhân viên (check-in/check-out)
        $entry = UserAttendance::firstOrCreate(
            [
                'date'    => date('Y-m-d'),
                'user_id' => $request->user()->id
            ],
            [
                'date'    => date('Y-m-d'),
                'user_id' => $request->user()->id
            ]
        );

        // Kiểm tra nếu là check-in (ci) hoặc check-out (co)
        if ($type == 'ci') {
            // Cập nhật thời gian check-in
            $entry->update(['datetime_ci' => date('Y-m-d H:i:s')]);

            // Điều kiện cho phép check-in sớm (ví dụ: check-in trước 08:00 vẫn hợp lệ)
            $checkInTime        = strtotime($entry->datetime_ci);
            $allowedCheckInTime = strtotime(date('Y-m-d 08:00:00')); // Check-in trước 08:00

            if ($checkInTime < $allowedCheckInTime) {
                // Check-in sớm hợp lệ, không cần yêu cầu lý do giải trình
                $entry->status = 'valid'; // Đánh dấu là hợp lệ
                $entry->save();
            } else {
                // Check-in sau 08:00, đánh dấu là không hợp lệ nếu có lỗi
                $entry->status = 'invalid'; // Đánh dấu là không hợp lệ nếu quá sớm hoặc lỗi
                $entry->save();
                return back()->withErrors(['datetime_ci' => 'Check-in time is invalid.']);
            }

        } else if ($type == 'co') {
            // Cập nhật thời gian check-out
            $entry->update(['datetime_co' => date('Y-m-d H:i:s')]);

            // Điều kiện cho phép check-out sau 17:00
            $checkOutTime        = strtotime($entry->datetime_co);
            $allowedCheckOutTime = strtotime(date('Y-m-d 17:00:00')); // Check-out sau 17:00

            if ($checkOutTime < $allowedCheckOutTime) {
                // Check-out trước 17:00 sẽ không hợp lệ
                $entry->status = 'invalid';
                $entry->save();

                // Hiển thị form giải trình lý do cho check-out trước 17:00
                return back()->withErrors(['datetime_co' => 'Check-out time is invalid, please provide an explanation.']);
            } else {
                // Check-out hợp lệ
                $entry->status = 'valid';
                $entry->save();
            }
        }

        return back()->with('success', sprintf('%s success', $type == 'ci' ? 'Check in' : 'Check out'));
    }





    public function submitExplanation(Request $request, $attendanceId)
    {
        // Tìm bản ghi Attendance theo ID
        $attendance = UserAttendance::findOrFail($attendanceId);

        // Kiểm tra nếu trạng thái là 'invalid' mới xử lý lý do giải trình
        if ($attendance->status === 'invalid') {
            // Cập nhật lý do giải trình và trạng thái
            $attendance->explanation = $request->input('explanation');
            $attendance->status      = 'pending';  // Đặt trạng thái thành 'pending'

            // Lưu vào cơ sở dữ liệu
            $attendance->save();

            // Thông báo thành công
            return back()->with('success', 'Explanation submitted successfully and status updated to pending.');
        } else {
            // Nếu trạng thái không phải 'invalid', trả về thông báo lỗi
            return back()->with('error', 'This attendance record is already valid or processed.');
        }
    }



    public function confirmExplanation($id)
    {
        // Tìm bản ghi attendance theo ID
        $attendance = UserAttendance::findOrFail($id);
        $user       = $attendance->user; // Lấy user liên quan đến attendance

        // Kiểm tra nếu status là 'pending', thay đổi trạng thái thành 'valid'
        if ($attendance->status == 'pending') {
            $attendance->status = 'valid'; // Hoặc có thể là trạng thái khác tùy yêu cầu
            $attendance->save(); // Lưu thay đổi

            // Gửi email thông báo cho nhân viên
            Mail::to($user->email)->send(new AttendanceStatusChanged($user, 'valid', null));

            return redirect()->route('user-attendance.show', $attendance->user_id)
                ->with('success', 'Explanation confirmed successfully.');
        }

        // Nếu không phải trạng thái 'pending', trả về lỗi
        return redirect()->route('user-attendance.show', $attendance->user_id)
            ->with('error', 'Explanation could not be confirmed.');
    }



    public function rejectExplanation($id)
    {
        // Tìm bản ghi attendance theo ID
        $attendance = UserAttendance::findOrFail($id);
        $user       = $attendance->user; // Lấy user liên quan đến attendance

        // Kiểm tra nếu status là 'pending', thay đổi trạng thái thành 'invalid'
        if ($attendance->status == 'pending') {
            $attendance->status = 'invalid'; // Hoặc có thể là trạng thái khác tùy yêu cầu
            $attendance->save(); // Lưu thay đổi

            // Gửi email thông báo cho nhân viên
            Mail::to($user->email)->send(new AttendanceStatusChanged($user, 'invalid', $attendance->explanation));

            return redirect()->route('user-attendance.show', $attendance->user_id)
                ->with('success', 'Explanation rejected successfully.');
        }

        // Nếu không phải trạng thái 'pending', trả về lỗi
        return redirect()->route('user-attendance.show', $attendance->user_id)
            ->with('error', 'Explanation could not be confirmed.');
    }




    public function changePassword(Request $request, $id)
    {
        $data     = $request->validate(['password' => ['required']]);
        $employee = User::findOrFail($id);
    }
}
