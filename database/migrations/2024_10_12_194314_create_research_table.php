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
        Schema::create('research', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->string('research_type_id')->nullable()->index();
            $table->string('department_id')->nullable()->index();
            $table->string('course_id')->nullable()->index();
            $table->string('yearlevel_id')->nullable()->index();
            $table->string('submitted_to_id')->nullable()->index();
            $table->string('submitted_by_id')->nullable()->index();
            $table->integer('chapter')->nullable();
            $table->decimal('version', 15, 2)->nullable()->default("0.00");
            $table->string('status')->nullable()->default('pending');
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
        Schema::dropIfExists('research');
    }
};
