@extends('layouts.app')

@section('title', 'Thêm phòng ban mới')

@section('content')
    <div class="form-container">
        <h2>New Department</h2>
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="parent_id" class="form-label">Parent:</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">NO</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status" class="form-label">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">ON</option>
                    <option value="0">OFF</option>
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