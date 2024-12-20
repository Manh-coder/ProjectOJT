@extends('layouts.app')

@section('title', 'Sửa thông tin nhân viên')

@section('content')
    <div class="form-container">
        <h2 class="mb-4">Edit Salary Level</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('salary-levels.update', $entry->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="position" class="form-label">Level:</label>
                <input type="text" name="level" id="position" class="form-control" value="{{ $entry->level }}" required>
            </div>
            <div class="form-group">
                <label for="monthly" class="form-label">Salary monthly:</label>
                <input type="number" name="monthly" id="monthly" value="{{ $entry->monthly }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="daily" class="form-label">Salary daily:</label>
                <input type="number" name="daily" id="daily" value="{{ $entry->daily }}" class="form-control" required>
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
