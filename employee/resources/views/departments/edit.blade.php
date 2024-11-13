@extends('layouts.app')

@section('title', 'Sửa thông tin phòng ban')

@section('content')
    <div class="form-container">
        <h2 class="mb-4">Edit Department</h2>
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $department->name }}" required>
            </div>
            <div class="form-group">
                <label for="parent_id" class="form-label">Parent:</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">NO</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" @if ($department->parent_id == $dept->id) selected @endif>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status" class="form-label">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" @if ($department->status == 1) selected @endif>ON</option>
                    <option value="0" @if ($department->status == 0) selected @endif>OFF</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
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

</style>