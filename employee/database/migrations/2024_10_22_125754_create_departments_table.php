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
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->string('name', 255);
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->integer('status');
        $table->timestamp('created_at')->useCurrent();
        $table->unsignedBigInteger('created_by');
        $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        $table->unsignedBigInteger('updated_by');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
