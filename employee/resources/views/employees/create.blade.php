@extends('layouts.app')

@section('title', 'Thêm nhân viên mới')

@section('content')
    <div class="form-container">
        <h2 class="mb-4">New Employee</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Phone:</label>
                <input type="tel" name="phone_number" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="age" class="form-label">Age:</label>
                <input type="number" name="age" id="age" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender" class="form-label">Gender:</label>
                <select name="gender" id="gender" class="form-control p-1">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department_id" class="form-label">Department:</label>
                <select name="department_id" id="department_id" class="form-control p-1">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="position" class="form-label">Position:</label>
                <input type="text" name="position" id="position" class="form-control">
            </div>
            <div class="form-group">
                <label for="salary_level_id" class="form-label">Satary level:</label>
                <select name="salary_level_id" id="salary_level_id" class="form-control p-1">
                    @foreach ($levels as $department)
                        <option value="{{ $department->id }}">{{ $department->level }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">ADD NEW</button>
            </div>
        </form>
    </div>
@endsection

<style>
    /* CSS tùy chỉnh để làm đẹp biểu mẫu */
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #343a40;
    }

    .form-container .form-group {
        margin-bottom: 15px;
    }

    .form-container .form-label {
        font-weight: bold;
        color: #495057;
    }

    .form-container .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px;
    }

    .form-container .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    .form-container .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .alert {
        margin-bottom: 20px;
    }
</style>
