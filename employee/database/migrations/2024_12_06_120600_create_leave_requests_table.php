<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID nhân viên
            $table->date('start_date');           // Ngày bắt đầu nghỉ
            $table->date('end_date');             // Ngày kết thúc nghỉ
            $table->text('reason')->nullable();   // Lý do nghỉ phép
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Trạng thái đơn
            $table->timestamps();

            // Khóa ngoại tham chiếu đến bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
}
