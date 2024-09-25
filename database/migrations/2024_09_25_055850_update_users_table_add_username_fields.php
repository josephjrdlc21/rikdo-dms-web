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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_info_id')->nullable()->index()->after('id');
            $table->string('username')->nullable()->after('email');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('status')->nullable()->default('inactive')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'last_login_at', 'status']);
        });
    }
};
