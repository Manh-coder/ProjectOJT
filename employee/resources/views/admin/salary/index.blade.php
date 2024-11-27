<!-- resources/views/admin/salary/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 style="font-weight: bold; font-size: 1.3rem;">List Employee Salary</h2><br>

<!-- Nút tính lương cho tất cả nhân viên -->
<a href="{{ route('salary.calculateAll') }}" class="btn btn-warning btn-xs" style="font-size: 12px; padding: 5px 9px;">
    <i class="fas fa-calculator"></i> Calculate All
</a>

    <a href="{{ route('salary.create') }}" class="btn btn-info btn-xs" style="font-size: 12px; padding: 5px 9px;">
        <i class="fas fa-calculator"></i> Calculate
    </a>
    
    

    <table class="table">
        <thead>
            <tr>
                <th>Employees</th>
                <th>Valid days</th>
                <th>Invalid days</th>
                <th>Total Salary</th>
                <th>Month</th>
                <th>Processed day</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $salary)
                <tr>
                    <td>{{ $salary->user->name }}</td>
                    <td>{{ $salary->valid_days }}</td>
                    <td>{{ $salary->invalid_days }}</td>
                    <td>{{ number_format($salary->salary, 0, '.', ',') }}</td>
                    <td>{{ $salary->month }}</td>
                    <td>{{ $salary->processed_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
