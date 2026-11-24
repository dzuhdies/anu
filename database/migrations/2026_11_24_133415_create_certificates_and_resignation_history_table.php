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
        if (Schema::hasTable('internship_applications') && !Schema::hasColumn('internship_applications', 'resignation_history')) {
            Schema::table('internship_applications', function (Blueprint $table) {
                $table->json('resignation_history')->nullable()->after('resignation_admin_note');
            });
        }

        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('student_id')->nullable()->index();
                $table->unsignedBigInteger('application_id')->nullable()->index();
                $table->unsignedBigInteger('division_id')->nullable();
                $table->string('certificate_number')->unique();
                $table->string('student_name');
                $table->string('institution')->nullable();
                $table->string('division_name')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->date('issue_date');
                $table->string('predicate')->default('Sangat Baik');
                $table->unsignedBigInteger('issued_by')->nullable();
                $table->string('signer_name')->default('Erny Fatma Setyoharini, S.E., M.Si.');
                $table->string('signer_position')->default('Kepala BPS Kota Malang');
                $table->string('status')->default('issued');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');

        if (Schema::hasTable('internship_applications') && Schema::hasColumn('internship_applications', 'resignation_history')) {
            Schema::table('internship_applications', function (Blueprint $table) {
                $table->dropColumn('resignation_history');
            });
        }
    }
};
