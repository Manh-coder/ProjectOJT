<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        Department::create([
            'name' => 'Phòng Nhân sự',
            'parent_id' => null,
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        Department::create([
            'name' => 'Phòng Kế toán',
            'parent_id' => null,
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
