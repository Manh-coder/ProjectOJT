<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveBalancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // ID nhân viên
            $table->integer('total_leave_days')->default(12); // Tổng số ngày nghỉ phép trong năm
            $table->integer('used_leave_days')->default(0);   // Số ngày đã sử dụng
            $table->integer('unpaid_leave_days')->default(0); // Số ngày nghỉ không lương
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
        Schema::dropIfExists('leave_balances');
    }
}
