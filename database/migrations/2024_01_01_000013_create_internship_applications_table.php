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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('divisions')->onDelete('restrict');
            $table->enum('type', ['individu', 'kelompok'])->default('individu');
            $table->string('group_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('suggested_start_date')->nullable();
            $table->date('suggested_end_date')->nullable();
            $table->string('status', 50)->default('pending');
            $table->boolean('is_over_quota')->default(false);
            $table->text('admin_note')->nullable();
            $table->text('resignation_reason')->nullable();
            $table->string('resignation_proof')->nullable();
            $table->timestamp('resignation_requested_at')->nullable();
            $table->foreignId('resignation_approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resignation_approved_at')->nullable();
            $table->text('resignation_admin_note')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->foreignId('rescheduled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rescheduled_at')->nullable();
            $table->text('applicant_rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
