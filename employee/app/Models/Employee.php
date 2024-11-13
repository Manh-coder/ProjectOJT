<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'employees';

    // Các thuộc tính có thể được gán giá trị hàng loạt
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'phone',
    //     'department_id',
    //     'position',
    //     'status',
    //     'created_by',
    //     'updated_by',
    // ];
    protected $guared = ['id'];
    // Định nghĩa mối quan hệ với bảng Department (một nhân viên thuộc về một phòng ban)
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }

    // Định nghĩa mối quan hệ với bảng UserAttendance (một nhân viên có nhiều lần chấm công)
    public function attendances()
    {
        return $this->hasMany(UserAttendance::class, 'user_id');
    }
}
