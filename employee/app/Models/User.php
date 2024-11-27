<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'type',
        'department_id',
        'position',
        'salary_level_id',
        'phone_number',
        'position'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    const TYPE_OPTIONS = [
        'admin'    => 1,
        'employee' => 2
    ];

    public function scopeTypeEmployee(Builder $builder)
    {
        $builder->where('type', self::TYPE_OPTIONS['employee']);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }


    public function attendances()
    {
        return $this->hasMany(UserAttendance::class, 'user_id');
    }
}

