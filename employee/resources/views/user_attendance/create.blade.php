@extends('layouts.app')

@section('title', 'New')

@section('content')
    <div class="form-container">
        <h2>New User-attendance</h2>
        <form action="{{ route('user-attendance.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id" class="form-label">Employee:</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="time" class="form-label">Time:</label>
                <input type="datetime-local" name="time" id="time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="type" class="form-label">Type:</label>
                <select name="type" id="type" class="form-control">
                    <option value="in">Check In</option>
                    <option value="out">Check Out</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">ADD</button>
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
    margin-bottom: 20px;
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

</style>