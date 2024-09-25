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
        Schema::create('users_kyc', function (Blueprint $table) {
            $table->id();
            $table->string('department_id')->nullable()->index();
            $table->string('course_id')->nullable()->index();
            $table->string('yearlevel_id')->nullable()->index();
            $table->string('id_number')->nullable();
            $table->string('role')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->string('processor_id')->nullable()->index();
            $table->datetime('process_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_kyc');
    }
};
