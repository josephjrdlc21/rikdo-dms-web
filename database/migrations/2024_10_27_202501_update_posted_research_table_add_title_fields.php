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
        Schema::table('posted_research', function (Blueprint $table) {
            $table->string('research_type_id')->nullable()->index()->after('processor_id');
            $table->text('title')->nullable()->after('abstract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posted_research', function (Blueprint $table) {
            $table->dropColumn(['research_type_id', 'title']);
        });
    }
};
