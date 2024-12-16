<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_leave_days',
        'used_leave_days',
        'unpaid_leave_days',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
