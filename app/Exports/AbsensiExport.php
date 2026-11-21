<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AbsensiExport implements FromView
{
    protected $siswaId, $tanggal, $bulan, $gedungId;

    public function __construct($siswaId, $tanggal, $bulan, $gedungId)
    {
        $this->siswaId = $siswaId;
        $this->tanggal = $tanggal;
        $this->bulan = $bulan;
        $this->gedungId = $gedungId;
    }

    public function view(): View
    {
        $query = StudentProfile::where('building_id', $this->gedungId);
        if ($this->siswaId) {
            $query->where('id', $this->siswaId);
        }

        if ($this->bulan) {
            $date = Carbon::createFromFormat('Y-m', $this->bulan);
            $absensiQuery = function ($q) use ($date) {
                $q->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month);
            };
        } elseif ($this->tanggal) {
            $absensiQuery = function ($q) {
                $q->whereDate('date', $this->tanggal);
            };
        } else {
            $absensiQuery = function ($q) {
                $q->orderBy('date', 'desc');
            };
        }

        $siswas = $query->with(['absensis' => $absensiQuery])->get();

        return view('pegawai.absensi_export', compact('siswas'));
    }
}
