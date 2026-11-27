<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('student_profiles', 'major')) {
            Schema::table('student_profiles', function (Blueprint $table) {
                $table->string('major')->nullable()->after('institution');
            });
        }

        // Split existing "University - Major" values into separate columns
        $rows = DB::table('student_profiles')->whereNotNull('institution')->get();
        foreach ($rows as $row) {
            $pos = strrpos($row->institution, ' - ');
            if ($pos !== false && empty($row->major)) {
                $univ = trim(substr($row->institution, 0, $pos));
                $major = trim(substr($row->institution, $pos + 3));
                DB::table('student_profiles')->where('id', $row->id)->update([
                    'institution' => $univ,
                    'major' => $major ?: null,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('student_profiles', 'major')) {
            Schema::table('student_profiles', function (Blueprint $table) {
                $table->dropColumn('major');
            });
        }
    }
};
