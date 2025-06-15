<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_entry', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('user_id');
            $table->integer('hours');
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('task')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entry');
    }
};
