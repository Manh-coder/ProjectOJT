<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'departments';

    // Các thuộc tính có thể được gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'parent_id',    
        'status',
        'created_by',
        'updated_by',
    ];

    // Định nghĩa mối quan hệ với bảng Employee (một phòng ban có nhiều nhân viên)
    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    // Định nghĩa mối quan hệ với phòng ban cha
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    // Định nghĩa mối quan hệ với các phòng ban con
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }


    public function subDepartments()
      {
    return $this->hasMany(Department::class, 'parent_id');
      }

}
