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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('name', 255);
        $table->string('email', 255)->unique();
        $table->string('phone', 20)->nullable();
        $table->unsignedBigInteger('department_id');
        $table->string('position', 100)->nullable();
        $table->integer('status')->default(1);
        $table->timestamp('created_at')->useCurrent();
        $table->unsignedBigInteger('created_by');
        $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        $table->unsignedBigInteger('updated_by');
        
        $table->foreign('department_id')->references('id')->on('departments');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
