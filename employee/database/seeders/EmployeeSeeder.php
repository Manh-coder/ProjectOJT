<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0123456789',
            'department_id' => 1,
            'position' => 'Nhân viên',
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        Employee::create([
            'name' => 'Trần Thị B',
            'email' => 'tranthib@example.com',
            'phone' => '0987654321',
            'department_id' => 2,
            'position' => 'Trưởng phòng',
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
