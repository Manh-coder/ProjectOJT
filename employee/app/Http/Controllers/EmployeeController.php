<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
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
        $keyword = $request->get('keyword');

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
        ]);

        // Thêm thông tin created_by
        $employeeData['created_by'] = auth()->id();
        $employeeData['updated_by'] = auth()->id();
        $employeeData['password']   = Hash::make($request->input('password'));

        User::create([
            ...$employeeData,
            'type' => User::TYPE_OPTIONS['employee']
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
            'position'        => ['required']
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
            return redirect()->back()->with('error', 'Failed to import employees. ' . $e->getMessage());
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
            $sheet->setCellValue('G1', 'Status');
            // 
            $row = 2;

            foreach ($employees as $employee) {
                $sheet->setCellValue('A' . $row, $employee->id);
                $sheet->setCellValue('B' . $row, $employee->name);
                $sheet->setCellValue('C' . $row, $employee->email);
                $sheet->setCellValue('D' . $row, $employee->phone);
                $sheet->setCellValue('E' . $row, $employee->department_id);
                $sheet->setCellValue('F' . $row, $employee->position);
                $sheet->setCellValue('G' . $row, $employee->status);
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
        if ($type == 'ci') {
            $entry->update(['datetime_ci' => date('Y-m-d H:i:s')]);
        } else {
            $entry->update(['datetime_co' => date('Y-m-d H:i:s')]);

        }
        return back()->with('success', sprintf('%s success', $type == 'ci' ? 'Check in' : 'Check out'));
    }

    public function changePassword(Request $request, $id)
    {
        $data     = $request->validate(['password' => ['required']]);
        $employee = User::findOrFail($id);
    }

}
