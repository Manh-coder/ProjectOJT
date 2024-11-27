<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('salaries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users'); // Liên kết với bảng users
        $table->integer('valid_days'); // Số ngày công hợp lệ
        $table->integer('invalid_days'); // Số ngày công không hợp lệ
        $table->decimal('salary', 10, 2); // Lương nhận được
        $table->string('month'); // Tháng tính lương (yyyy-mm)
        $table->foreignId('processed_by')->nullable()->constrained('users'); // Người xử lý
        $table->timestamp('processed_at')->nullable(); // Thời gian xử lý
        $table->foreignId('updated_by')->nullable()->constrained('users'); // Người cập nhật
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
