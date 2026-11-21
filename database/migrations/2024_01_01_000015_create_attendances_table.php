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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->onDelete('cascade');
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->string('punctuality_status')->nullable();
            $table->time('check_out_time')->nullable();
            $table->boolean('work_type')->default(false);
            $table->boolean('attendance_status')->default(false);
            $table->string('check_in_photo')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->string('check_out_photo')->nullable();
            $table->boolean('is_permission')->default(false);
            $table->foreignId('leave_type_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->text('leave_description')->nullable();
            $table->string('leave_file')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
