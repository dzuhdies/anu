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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->date('wfh_start_date')->nullable()->after('end_date');
            $table->date('wfh_end_date')->nullable()->after('wfh_start_date');
            $table->string('wfh_notes', 255)->nullable()->after('wfh_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['wfh_start_date', 'wfh_end_date', 'wfh_notes']);
        });
    }
};
