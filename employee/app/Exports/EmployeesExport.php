<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromQuery, WithChunkReading, WithHeadings
{
    public function query()
    {
        return Employee::query();
    }

    public function chunkSize(): int
    {
        return 50000;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Department ID',
            'Position',
            'Status',
        ];
    }
}
