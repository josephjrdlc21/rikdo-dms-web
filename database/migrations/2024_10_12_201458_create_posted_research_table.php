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
        Schema::create('posted_research', function (Blueprint $table) {
            $table->id();
            $table->text('abstract')->nullable();
            $table->string('department_id')->nullable()->index();
            $table->string('course_id')->nullable()->index();
            $table->string('yearlevel_id')->nullable()->index();
            $table->string('processor_id')->nullable()->index();
            $table->text('authors')->nullable();
            $table->string('source')->nullable();
            $table->string('filename')->nullable();
            $table->text('directory')->nullable();
            $table->text('path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posted_research');
    }
};
