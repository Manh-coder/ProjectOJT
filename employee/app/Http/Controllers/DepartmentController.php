<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //Hiển thị danh sách phòng ban

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        // $departments = Department::withCount('users')->get();
        //$departments = Department::with('parent')->paginate(10); // Sử dụng with() để tải thông tin phòng ban cha
        $departments = Department::query()
            ->when(
                $keyword,
                fn($q) => $q->where('name', 'like', "%$keyword%")
                    ->orWhere('status', 'like', "%$keyword%")
            )
            ->with('children')->whereNull('parent_id')
            ->paginate(10);

        return view('departments.index', compact('departments'));
    }


    // Hiển thị form tạo mới phòng ban
    public function create()
    {
        $departments = Department::all(); // Lấy tất cả phòng ban để chọn parent_id
        return view('departments.create', compact('departments'));
    }

    // Lưu phòng ban mới
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:departments,name',
            'status' => 'required|integer',
        ]);

        // Thêm thông tin created_by
        $departmentData               = $request->all();
        $departmentData['created_by'] = auth()->id();
        $departmentData['updated_by'] = auth()->id();

        Department::create($departmentData);
        return redirect()->route('departments.index')->with('success', 'New Department successfully.');
    }


    // Hiển thị form chỉnh sửa thông tin phòng ban
    public function edit($id)
    {
        $department  = Department::findOrFail($id);
        $departments = Department::all(); // Lấy tất cả phòng ban để chọn parent_id
        return view('departments.edit', compact('department', 'departments'));
    }

    // Cập nhật thông tin phòng ban
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255|unique:departments,name,' . $id,
            'status' => 'required|integer',
        ]);

        // Cập nhật thông tin updated_by
        $departmentData               = $request->all();
        $departmentData['updated_by'] = auth()->id();

        $department->update($departmentData);
        return redirect()->route('departments.index')->with('success', 'Update Department successfully.');
    }

    // Xóa phòng ban
    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra nếu lỗi liên quan đến ràng buộc khóa ngoại
            if ($e->getCode() == 23000) {
                return redirect()->route('departments.index')->with('error', 'Cannot delete department because there are related attendance records.');
            }
            // Xử lý các ngoại lệ khác (nếu có)
            return redirect()->route('departments.index')->with('error', 'An error occurred while deleting the employee.');
        }
    }


    // Tìm kiếm phòng ban
    // Sửa phương thức search() để hỗ trợ phân trang
    public function search(Request $request)
    {

    }

    public function show($id)
    {
        $department = Department::withCount('employeess')->findOrFail($id);
        return view('departments.details', compact('department'));
    }


}
