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
        Schema::table('users_info', function (Blueprint $table) {
            $table->string('source')->nullable()->after('address');
            $table->string('filename')->nullable()->after('source');
            $table->text('directory')->nullable()->after('filename');
            $table->text('path')->nullable()->after('directory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_info', function (Blueprint $table) {
            $table->dropColumn(['source', 'filename', 'directory', 'path']);
        });
    }
};
