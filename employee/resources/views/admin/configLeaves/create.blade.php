@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 style="font-weight: bold; font-size: 1.3rem;">Calculate Employee Salary</h2><br>

        {{-- Display success or error message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('salary.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">Select Employee</label>
                <select class="form-control" name="user_id" id="user_id">
                    <option value="" selected disabled>Select an employee</option>
                    @foreach ($users->slice(1) as $user)
                        <!-- Sử dụng slice để bỏ qua phần tử đầu tiên -->
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="valid_days">Valid Workdays</label>
                <input type="text" class="form-control" name="valid_days" id="valid_days" readonly>
            </div>

            <div class="form-group">
                <label for="invalid_days">Invalid Workdays</label>
                <input type="text" class="form-control" name="invalid_days" id="invalid_days" readonly>
            </div>

            <div class="form-group">
                <label for="daily" class="form-label">Month</label>
                <select class="form-select" aria-label="Default select example" name="month">
                    <option value="">Open this select month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <br>


            <button type="submit" class="btn btn-primary">Calculate Salary</button>
        </form>
    </div>

@endsection


@push('scripts')
    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            const userId = this.value;
            console.log(userId);

            if (userId) {
                // Gửi request tới backend để lấy số ngày hợp lệ và không hợp lệ
                fetch(`/admin/salary/get-attendance-days/${userId}`)
                    .then(response => {
                        // Kiểm tra nếu response không thành công
                        if (!response.ok) {
                            console.error('Failed to fetch data', response.status);
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Kiểm tra nếu dữ liệu trả về hợp lệ
                        if (data && data.valid_days !== undefined && data.invalid_days !== undefined) {
                            document.getElementById('valid_days').value = data.valid_days;
                            document.getElementById('invalid_days').value = data.invalid_days;
                        } else {
                            console.error('Invalid data structure:', data);
                            alert('Invalid data returned. Please try again later.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching attendance days:', error);
                        alert('Failed to load attendance data. Please try again later.');
                    });
            }
        });
    </script>
@endpush
