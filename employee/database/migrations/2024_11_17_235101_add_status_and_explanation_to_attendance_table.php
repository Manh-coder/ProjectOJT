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
    Schema::table('user_attendance', function (Blueprint $table) {
        $table->text('explanation')->nullable(); // Trường lưu lý do giải trình
        $table->enum('status', ['valid', 'invalid', 'pending'])->default('invalid'); // Trạng thái
        $table->boolean('is_confirmed')->default(false);  // Thêm trường 'is_confirmed' (Xác nhận), giá trị mặc định là false
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('user_attendance', function (Blueprint $table) {
        $table->dropColumn(['status', 'explanation', 'is_confirmed']);  // Xóa các trường đã thêm
    });
}

};
