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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable()->index();
            $table->boolean('has_research')->nullable();
            $table->boolean('has_student_research')->nullable();
            $table->boolean('has_all_research')->nullable();
            $table->boolean('has_completed_research')->nullable();
            $table->boolean('has_posted_research')->nullable();
            $table->boolean('has_archives')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
