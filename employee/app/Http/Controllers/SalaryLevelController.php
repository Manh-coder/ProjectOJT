<?php

namespace App\Http\Controllers;

use App\Models\SalaryLevel;
use Illuminate\Http\Request;

class SalaryLevelController extends Controller
{
    // Hiển thị danh sách nhân viên
    // Sửa phương thức index() trong EmployeeController
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $employees = SalaryLevel::paginate(10); // Số lượng nhân viên hiển thị trên mỗi trang

        return view('salary.index', compact('employees'));
    }


    // Hiển thị form tạo mới nhân viên
    public function create()
    {
        return view('salary.create');
    }

    // Lưu nhân viên mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'level'   => 'required',
            'monthly' => 'required',
            'daily'   => 'required',
        ]);

        SalaryLevel::create($data);
        return redirect()->route('salary-levels.index')->with('success', 'New salary created successfully.');
    }



    // Hiển thị form chỉnh sửa thông tin nhân viên
    public function edit($id)
    {
        $entry = SalaryLevel::findOrFail($id);
        return view('salary.edit', compact('entry'));
    }

    public function update(Request $request, $id)
    {
        $employee = SalaryLevel::findOrFail($id);

        $valid = $request->validate([
            'level'   => 'required',
            'monthly' => 'required',
            'daily'   => 'required',
        ]);

        // Cập nhật thông tin updated_by

        $employee->update($valid);
        return redirect()->route('salary-levels.index')->with('success', 'Employee updated successfully.');
    }



    // Xóa nhân viên
    public function destroy($id)
    {
        try {
            $employee = SalaryLevel::findOrFail($id);
            $employee->delete();

            return redirect()->route('salary-levels.index')->with('success', 'Employee deleted successfully.');
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
    // public function search(Request $request)
    // {
    //     $keyword = $request->get('keyword');

    //     $employees = User::query()
    //         ->when(
    //             $keyword,
    //             fn($q) => $q->where('name', 'like', "%$keyword%")
    //                 ->orWhere('email', 'like', "%$keyword%")
    //         )
    //         ->paginate(4); // Số lượng nhân viên hiển thị trên mỗi trang

    //     return view('employees.index', compact('employees'));
    // }




    public function show($id)
    {
        $employee = SalaryLevel::findOrFail($id);
        return view('salary.show', compact('employee'));
    }
}
