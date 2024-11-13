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
    Schema::create('user_attendance', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->dateTime('time');
        $table->enum('type', ['in', 'out']);
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('updated_by');
        $table->timestamps();
        // $table->foreign('user_id')->references('id')->on('employees');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attendances');
    }

    
};
