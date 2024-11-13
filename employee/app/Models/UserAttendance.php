<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttendance extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'user_attendance';

    // Các thuộc tính có thể được gán giá trị hàng loạt
    // protected $fillable = [
    //     'user_id',
    //     'time',
    //     'type',
    //     'created_by',
    //     'updated_by',
    // ];
    protected $guarded = ['id'];

    // Định nghĩa mối quan hệ với bảng Employee (một lần chấm công thuộc về một nhân viên)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
