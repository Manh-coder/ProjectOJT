<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttendance extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'user_attendance';

    
    protected $guarded = ['id',
    'explanation',
    'status',
    'is_confirmed'
            ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
