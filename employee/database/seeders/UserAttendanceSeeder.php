<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAttendance;

class UserAttendanceSeeder extends Seeder
{
    public function run()
    {
        UserAttendance::create([
            'user_id' => 1,
            'time' => now(),
            'type' => 'in',
            'created_by' => 1,
            'updated_by' => 1
        ]);

        UserAttendance::create([
            'user_id' => 2,
            'time' => now(),
            'type' => 'out',
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
