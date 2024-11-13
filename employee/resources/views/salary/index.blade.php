@extends('layouts.app')

@section('title', 'Salary level')

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
    <h2 style="font-size: 1.5rem; font-weight: bold;">Salary</h2><br>




    <!-- Search Form - căn phải -->
    {{-- <div class="mb-4">
        <form action="{{ route('employees.index') }}" method="GET" class="form-inline float-right">
            <input type="text" name="keyword" placeholder="Search employees..." class="form-input"
                value="{{ request()->get('keyword') }}">
            <button type="submit" class="button-sm" style="background-color: #6c757d; color: #fff;">Search</button>
        </form>
    </div> --}}

    <a href="{{ route('salary-levels.create') }}" class="button-sm" style="background-color: #17a2b8; color: #fff;">New</a>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Level</th>
                <th>Monthly</th>
                <th>Daily</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $key => $employee)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $employee->level }}</td>
                    <td>{{ Number::format($employee->monthly) }}</td>
                    <td>{{ Number::format($employee->daily) }}</td>

                    <td>
                        <a href="{{ route('salary-levels.show', $employee->id) }}" class="button-sm" style="background-color: #007bff; color: #fff;">Details</a>
                        <a href="{{ route('salary-levels.edit', $employee->id) }}" class="button-sm" style="background-color: #ffc107; color: #000;">Edit</a>
                        <form action="{{ route('salary-levels.destroy', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-sm" style="background-color: #dc3545; color: #fff;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
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


@endsection
