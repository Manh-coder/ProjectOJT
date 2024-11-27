{{-- @extends('layouts.app')

@section('title', 'Employee List')

<style>
    .form-input {
        padding: 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ddd;
        margin-right: 0.5rem;
    }
</style>

@section('content')
<h2 style="font-size: 1.5rem; font-weight: bold;">Employee List</h2><br>

    <!-- Search Form -->
<form action="{{ route('employees.index') }}" method="GET" class="mb-4">
    <input type="text" name="keyword" placeholder="Search employees..." class="form-input" value="{{ request()->get('keyword') }}">
    <button type="submit" class="btn">Search</button>
</form>


    <a href="{{ route('employees.create') }}" class="btn">New Employee</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($employees->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">No employees found.</td>
                </tr>
            @else
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>                        
                        <td>{{ $employee->department->name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn">View</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn">Edit</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Hiển thị phân trang -->
<div class="mt-4">
    {{ $employees->links() }}
</div>

<!-- JavaScript để hiển thị hộp thoại xác nhận -->
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this employee?');
    }
</script>


@if (session('error'))
    <div class="alert alert-danger" style="color: red;">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" style="color: rgb(13, 26, 170)">
        {{ session('success') }}
    </div>
@endif

@endsection --}}


@extends('layouts.app')

@section('title', 'Employee List')

<style>
    .form-input {
        padding: 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ddd;
        margin-right: 0.5rem;
    }

    /* CSS cho việc căn chỉnh và thu nhỏ các nút */
    .button-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    /* CSS cho việc căn chỉnh form Import và Export */
    .form-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    /* CSS để căn phải thanh tìm kiếm */
    .float-right {
        float: right;
    }
</style>

@section('content')
    <h2 style="font-size: 1.5rem; font-weight: bold;">Employee List</h2><br>


    <div class="form-container">

        <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="d-flex">
            @csrf
            <input type="file" name="file" class="form-input" required>
            <button type="submit" class="button-sm" style="background-color: #007bff; color: #fff;">Import</button>
        </form>

        <!-- Nút Export -->
        {{-- <a href="{{ route('employees.export-phpspreadsheet') }}" class="button-sm" style="background-color: #28a745; color: #fff;">Export with PhpSpreadsheet</a> --}}

        <a href="{{ route('employees.export', ['per_page' => 5]) }}" class="button-sm" style="background-color: #28a745; color: #fff;">Export</a>
    </div>


    <!-- Search Form - căn phải -->
    <div class="mb-4">
        <form action="{{ route('employees.index') }}" method="GET" class="form-inline float-right">
            <input type="text" name="keyword" placeholder="Search employees..." class="form-input" value="{{ request()->get('keyword') }}">
            <button type="submit" class="button-sm" style="background-color: #6c757d; color: #fff;">Search</button>
        </form>
    </div>

    <a href="{{ route('employees.create') }}" class="button-sm" style="background-color: #17a2b8; color: #fff;">New Employee</a>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Department</th>
                {{-- <th>Salary Level</th> --}}
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($employees->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">No employees found.</td>
                </tr>
            @else
                @foreach ($employees as $key => $employee)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->department?->name }}</td>
                        {{-- <td>{{ $employee->salaryLevel?->level }}</td> --}}
                        <td>{{ $employee->position }}</td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="button-sm" style="background-color: #007bff; color: #fff;">Details</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="button-sm" style="background-color: #ffc107; color: #000;">Edit</a>
                            
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-sm" style="background-color: #dc3545; color: #fff;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Hiển thị phân trang -->
    <div class="mt-4">
        {{ $employees->links() }}
    </div>

    <!-- JavaScript để hiển thị hộp thoại xác nhận -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this employee?');
        }
    </script>

    @if (session('error'))
        <div class="alert alert-danger" style="color: red;">
            {{ session('error') }}
        </div>
    @endif



@endsection
