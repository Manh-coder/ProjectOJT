<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Gọi các seeder khác, ví dụ:
        $this->call([
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            UserAttendanceSeeder::class,
        ]);
    }
}
