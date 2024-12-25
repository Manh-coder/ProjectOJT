<!-- resources/views/departments/details.blade.php -->
@extends('layouts.app')

@section('title', 'Department Details')

<style>
    .department-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        max-width: 700px;
        margin: 2rem auto;
        text-align: left; /* Căn lề trái */
    }

    .department-card h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: #333;
        border-bottom: 2px solid #3182CE;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .department-info {
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: #555;
    }

    .department-info strong {
        color: #222;
    }

    .btn {
        background-color: #3182CE;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 1.5rem;
        transition: background-color 0.3s ease;
        font-size: 1rem;
    }

    .btn:hover {
        background-color: #2B6CB0;
    }
</style>

@section('content')
<div class="department-card">
    <h2>Department Details</h2>

    <div class="department-info"><strong>ID:</strong> {{ $department->id }}</div>
    <div class="department-info"><strong>Name:</strong> {{ $department->name }}</div>
    <div class="department-info"><strong>Parent Department:</strong> {{ $department->parent ? $department->parent->name : 'Not available' }}</div>
    <div class="department-info"><strong>Status:</strong> {{ $department->status == 1 ? 'Activate' : 'Disable' }}</div>
    <div class="department-info"><strong>Number of Employees:</strong> {{ $department->users_count }}</div>

    <a href="{{ route('departments.index') }}" class="btn">Back to Departments List</a>
</div>
@endsection
