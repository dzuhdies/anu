<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView
{
    protected $studentId, $date, $month, $buildingId;

    public function __construct($studentId, $date, $month, $buildingId)
    {
        $this->studentId = $studentId;
        $this->date = $date;
        $this->month = $month;
        $this->buildingId = $buildingId;
    }

    public function view(): View
    {
        $query = StudentProfile::where('building_id', $this->buildingId);
        if ($this->studentId) {
            $query->where('id', $this->studentId);
        }

        if ($this->month) {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->month);
            $attendanceQuery = function ($q) use ($carbonDate) {
                $q->whereYear('date', $carbonDate->year)
                    ->whereMonth('date', $carbonDate->month);
            };
        } elseif ($this->date) {
            $attendanceQuery = function ($q) {
                $q->whereDate('date', $this->date);
            };
        } else {
            $attendanceQuery = function ($q) {
                $q->orderBy('date', 'desc');
            };
        }

        $siswas = $query->with(['attendances' => $attendanceQuery])->get();

        return view('pegawai.absensi_export', compact('siswas'));
    }
}
