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
        Schema::table('research', function (Blueprint $table) {
            $table->string('modified_by_id')->nullable()->index()->after('submitted_to_id');
            $table->string('process_by_id')->nullable()->index()->after('modified_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropColumn(['modified_by_id', 'process_by_id']);
        });
    }
};
