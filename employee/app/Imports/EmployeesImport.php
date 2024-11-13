<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name'            => $row['name'],
            'username'        => $row['name'],
            'password'        => Hash::make($row['password']),
            'email'           => $row['email'],
            'phone'           => $row['phone'],
            'department_id'   => $row['department_id'],
            'position'        => $row['position'],
            'salary_level_id' => $row['salary_level_id'],
            'type'            => User::TYPE_OPTIONS['employee']
        ]);
    }
}
