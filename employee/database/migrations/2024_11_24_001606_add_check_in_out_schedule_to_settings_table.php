<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckInOutScheduleToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('user_attendance', function (Blueprint $table) {
            $table->time('check_in_time')->default('07:00'); // Giờ check-in mặc định
            $table->time('check_out_time')->default('17:00'); // Giờ check-out mặc định
        });
    }

    public function down()
    {
        Schema::table('user_attendance', function (Blueprint $table) {
            $table->dropColumn(['check_in_time', 'check_out_time']);
        });
    }
}

