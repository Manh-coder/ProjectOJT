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

    // Lưu bac luong mới
    public function store(Request $request)
{
    $data = $request->validate([
        'level'   => 'required',
        'monthly' => 'required|numeric',
        'daily'   => 'required|numeric',
    ], [
        'monthly.numeric' => 'Lương tháng phải là một số.',
        'daily.numeric'   => 'Lương ngày phải là một số.',
    ]);

    // Kiểm tra nếu lương ngày lớn hơn lương tháng
    if ($data['daily'] > $data['monthly']) {
        return back()->withErrors(['daily' => 'Daily salary cannot be greater than monthly salary.']);
    }

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

    // Validate input fields
    $valid = $request->validate([
        'level'   => 'required',
        'monthly' => 'required|numeric',
        'daily'   => 'required|numeric',
    ], [
        'monthly.numeric' => 'Lương tháng phải là một số.',
        'daily.numeric'   => 'Lương ngày phải là một số.',
    ]);

    // Kiểm tra lương ngày không được lớn hơn lương tháng và ngược lại
    if ($valid['daily'] > $valid['monthly']) {
        return back()->withErrors(['daily' => 'Daily salary cannot be greater than monthly salary.']);
    }

    // Cập nhật thông tin
    $employee->update($valid);

    return redirect()->route('salary-levels.index')->with('success', 'Salary updated successfully.');
}




    // Xóa nhân viên
    public function destroy($id)
    {
        try {
            $employee = SalaryLevel::findOrFail($id);
            $employee->delete();

            return redirect()->route('salary-levels.index')->with('success', 'Salary deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra nếu lỗi liên quan đến khóa ngoại
            if ($e->getCode() == 23000) {
                return back()->with('error', 'Cannot delete Salary because there are related attendance records.');
            }
            // Xử lý các ngoại lệ khác (nếu có)
            return back()->with('error', 'An error occurred while deleting the Salary.');
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
