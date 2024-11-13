<!-- resources/views/employees/show.blade.php -->
@extends('layouts.app')

@section('title', 'Employee Details')

<style>
    .employee-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .employee-card h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #2D3748;
    }

    .employee-info {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #4A5568;
    }

    .employee-info strong {
        color: #2D3748;
    }

    .btn {
        background-color: #3182CE;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
    }

    .btn:hover {
        background-color: #2B6CB0;
    }
</style>

@section('content')
    <div class="employee-card">
        <h2>Salary level Details</h2>

        <div class="employee-info"><strong>ID:</strong> {{ $employee->id }}</div>
        <div class="employee-info"><strong>Level:</strong> {{ $employee->level }}</div>
        <div class="employee-info"><strong>Monthly:</strong> {{ Number::format($employee->monthly) }}</div>
        <div class="employee-info"><strong>Daily:</strong> {{ Number::format($employee->daily) }}</div>
        <h3>Danh sách nhân viên: </h3>
        @foreach ($employee->users as $item)
            <p>- {{ $item->name }}</p>
        @endforeach

        <a href="{{ route('salary-levels.index') }}" class="btn">Back to Salary Level List</a>
    </div>
@endsection
