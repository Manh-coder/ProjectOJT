{{-- @extends('layouts.app')

@section('title', 'Departments List')

<style>
    .form-input {
        padding: 0.3rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ddd;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    /* CSS cho việc thu nhỏ và thay đổi màu của các nút */
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
        border-radius: 0.25rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background-color: #bd2130;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

@section('content')

<h2 style="font-size: 1.5rem; font-weight: bold;">Departments List</h2><br>

<!-- Search Form -->
<form action="{{ route('departments.index') }}" method="GET" class="mb-4">
    <input type="text" name="keyword" placeholder="Search departments..." class="form-input" value="{{ request()->get('keyword') }}">
    <button type="submit" class="btn-sm btn-secondary">Search</button>
</form>

<!-- New Department Button -->
<a href="{{ route('departments.create') }}" class="btn-sm btn-primary" style="margin-bottom: 1rem; display: inline-block;">New Department</a>

<!-- Departments Table -->
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if ($departments->isEmpty())
            <tr>
                <td colspan="5" class="text-center">No departments found.</td>
            </tr>
        @else
            @foreach ($departments as $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->parent ? $department->parent->name : 'Không có' }}</td>
                    <td>{{ $department->status == 1 ? 'Kích hoạt' : 'Vô hiệu hóa' }}</td>
                    <td>
                        <a href="{{ route('departments.details', $department->id) }}" class="btn-sm btn-primary">Details</a>
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn-sm btn-primary" style="background-color: #ffc107; color: #000;">Edit</a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                    
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<!-- Hiển thị phân trang -->
<div class="mt-4">
    {{ $departments->links() }}
</div>

@if (session('error'))
    <div class="alert alert-danger" style="color: red;">
        {{ session('error') }}
    </div>
@endif

@endsection --}}





@extends('layouts.app')

@section('title', 'Departments Tree')

@section('content')

    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <style>
        .tree-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 2rem;
            font-family: Arial, sans-serif;
            width: 100%;
            max-width: 800px;
        }

        /* Khung bao quanh tất cả các phòng ban */
        .department-wrapper {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f7f7f7;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        /* Form tìm kiếm và nút thêm mới */
        .search-form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .form-input {
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: 1px solid #ddd;
            font-size: 0.875rem;
            width: 100%;
        }

        .btn-primary {
            padding: 0.5rem 1.5rem;
            border-radius: 0.55rem;
            font-size: 0.875rem;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        /* Tree View */
        .tree {
            list-style-type: none;
            padding-left: 20px;
            position: relative;
            width: 100%;
        }

        .tree ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .tree ul li {
            padding: 10px 5px;
            position: relative;
        }

        .tree-node {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #93ba31;
            color: #ffffff;
            font-weight: bold;
            min-width: 120px;
            text-align: center;
            transition: background-color 0.3s ease;
            position: relative;
            cursor: pointer;
            text-decoration: none;
        }

        .tree-node:hover {
            background-color: #007bff;
            color: #fff;
        }

        /* Sub-department node style */
        .sub-department {
            display: none;
        }

        /* Arrow icon styling */
        .arrow {
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: #007bff;
            transition: transform 0.3s;
        }

        .arrow-right {
            transform: rotate(-90deg);
        }

        .arrow-down {
            transform: rotate(0deg);
        }

        /* Action buttons for edit and delete */
        .action-buttons {
            margin-left: 15px;
        }

        .btn-edit,
        .btn-delete {
            font-size: 0.875rem;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            margin-left: 5px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-delete {
            background-color: #dc3545;
        }
    </style>

    <h2 style="text-align: center; font-size: 1.75rem; font-weight: bold;">Departments List</h2>

    <!-- Search Form -->
    <div class="tree-container">
        <form action="{{ route('departments.index') }}" method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="Search departments..." class="form-input"
                value="{{ request()->get('keyword') }}">
            <button type="submit" class="btn-primary">Search</button>
            <a href="{{ route('departments.create') }}" class="btn-primary">NEW</a>
        </form>

        <!-- Wrapper for all departments -->
        <div class="department-wrapper">
            <div class="tree">
                <ul>
                    @foreach ($departments as $department)
                        <li>
                            <i id="arrow-{{ $department->id }}" class="fas fa-chevron-right arrow arrow-right"
                                onclick="toggleSubDepartments({{ $department->id }})"></i>
                            <a href="{{ route('departments.show', $department->id) }}"
                                class="tree-node">{{ $department->name }}</a>
                            <span class="action-buttons">
                                <a href="{{ route('departments.edit', $department->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"
                                        onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                </form>
                            </span>

                            @if ($department->children && $department->children->isNotEmpty())
                                <ul id="sub-departments-{{ $department->id }}" class="sub-department">
                                    @foreach ($department->children as $subDepartment)
                                        <li>
                                            <i id="arrow-{{ $subDepartment->id }}"
                                                class="fas fa-chevron-right arrow arrow-right"
                                                onclick="toggleSubDepartments({{ $subDepartment->id }})"></i>
                                            <a href="{{ route('departments.show', $subDepartment->id) }}"
                                                class="tree-node">{{ $subDepartment->name }}</a>
                                            <span class="action-buttons">
                                                <a href="{{ route('departments.edit', $subDepartment->id) }}"
                                                    class="btn-edit">Edit</a>
                                                <form action="{{ route('departments.destroy', $subDepartment->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete"
                                                        onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                                </form>
                                            </span>

                                            @if ($subDepartment->children && $subDepartment->children->isNotEmpty())
                                                <ul id="sub-departments-{{ $subDepartment->id }}" class="sub-department">
                                                    @foreach ($subDepartment->children as $childDepartment)
                                                        <li>
                                                            <a href="{{ route('departments.show', $childDepartment->id) }}"
                                                                class="tree-node">{{ $childDepartment->name }}</a>
                                                            <span class="action-buttons">
                                                                <a href="{{ route('departments.edit', $childDepartment->id) }}"
                                                                    class="btn-edit">Edit</a>
                                                                <form
                                                                    action="{{ route('departments.destroy', $childDepartment->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-delete"
                                                                        onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                                                </form>
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleSubDepartments(id) {
            const arrow = document.getElementById(`arrow-${id}`);
            const subDepartments = document.getElementById(`sub-departments-${id}`);

            if (subDepartments && (subDepartments.style.display === "none" || subDepartments.style.display === "")) {
                subDepartments.style.display = "block";
                arrow.classList.remove("fa-chevron-right");
                arrow.classList.add("fa-chevron-down");
            } else if (subDepartments) {
                subDepartments.style.display = "none";
                arrow.classList.remove("fa-chevron-down");
                arrow.classList.add("fa-chevron-right");
            }
        }
    </script>

@endsection
