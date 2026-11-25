<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Division extends Model
{
    protected $table = 'divisions';

    protected $fillable = [
        'name',
        'description',
        'quota',
        'is_active',
        'activities',
    ];

    protected $casts = [
        'quota' => 'integer',
        'is_active' => 'boolean',
        'activities' => 'array',
    ];

    protected $appends = [
        'nama_divisi',
        'deskripsi',
        'kuota',
        'aktivitas_list',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(InternshipApplication::class, 'division_id');
    }

    public function internshipApplications(): HasMany
    {
        return $this->applications();
    }

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'division_id');
    }

    public function employeeProfiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class, 'division_id');
    }

    // Backward-compatible relations
    public function pendaftarans(): HasMany
    {
        return $this->applications();
    }

    public function siswaProfiles(): HasMany
    {
        return $this->studentProfiles();
    }

    // Accessors / Mutators
    public function getRemainingQuotaAttribute(): int
    {
        $todayStr = now()->toDateString();
        $occupiedToday = $this->getOccupiedOnDate($todayStr);
        return max(0, (int)$this->quota - $occupiedToday);
    }

    public function getCurrentOccupiedAttribute(): int
    {
        $todayStr = now()->toDateString();
        return $this->getOccupiedOnDate($todayStr);
    }

    // Backward-compatible accessors
    public function getNamaDivisiAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function setNamaDivisiAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function getDeskripsiAttribute()
    {
        return $this->attributes['description'] ?? null;
    }

    public function setDeskripsiAttribute($value)
    {
        $this->attributes['description'] = $value;
    }

    public function getKuotaAttribute(): int
    {
        return (int) ($this->attributes['quota'] ?? 0);
    }

    public function setKuotaAttribute($value)
    {
        $this->attributes['quota'] = (int) $value;
    }

    public function getSisaKuotaAttribute(): int
    {
        return $this->getRemainingQuotaAttribute();
    }

    public function getTerisiSaatIniAttribute(): int
    {
        return $this->getCurrentOccupiedAttribute();
    }

    public function getAktivitasListAttribute(): array
    {
        $raw = $this->activities;
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }
        if (!empty($raw) && is_array($raw)) {
            return array_values(array_filter(array_map('trim', $raw)));
        }
        return $this->getDefaultActivities();
    }

    public function getDefaultActivities(): array
    {
        $lowerName = strtolower($this->name ?? '');
        if (str_contains($lowerName, 'ipds') || str_contains($lowerName, 'integrasi')) {
            return [
                'Pengembangan dan pemeliharaan website/sistem informasi internal BPS.',
                'Perancangan visualisasi data interaktif, dashboard analitik, dan infografis publikasi.',
                'Pengelolaan basis data hasil survei dan integrasi data sensus statistik.',
                'Pelayanan konsultasi data statistik di Pelayanan Statistik Terpadu (PST) dan Pojok Statistik.',
                'Dukungan jaringan, pemeliharaan perangkat keras, dan keamanan sistem informasi kantor.'
            ];
        } elseif (str_contains($lowerName, 'sosial')) {
            return [
                'Validasi, konsistensi, dan entri dokumen survei sosial ekonomi (Susenas dan Sakernas).',
                'Penyusunan tabulasi data kemiskinan, ketimpangan pengeluaran (Gini Ratio), dan IPM.',
                'Pengolahan indikator ketenagakerjaan, upah buruh, serta statistik gender dan pendidikan.',
                'Penyusunan draft publikasi profil statistik kependudukan dan sosial Kota Malang.',
                'Pemeriksaan kelengkapan berkas kuesioner pendataan lapangan.'
            ];
        } elseif (str_contains($lowerName, 'produksi')) {
            return [
                'Pemutakhiran direktori perusahaan industri manufaktur skala besar, sedang, dan mikro/kecil.',
                'Pengolahan data survei statistik pertanian tanaman pangan, hortikultura, dan peternakan.',
                'Pencatatan dan verifikasi data survei perusahaan konstruksi dan bahan galian.',
                'Penyusunan laporan indeks produksi industri manufaktur dan profil komoditas daerah.',
                'Rekapitulasi dan pelaporan berkas bulanan data produksi unggulan daerah.'
            ];
        } elseif (str_contains($lowerName, 'distribusi')) {
            return [
                'Pencatatan dan pengolahan Survei Harga Konsumen (SHK) untuk perhitungan inflasi Kota Malang.',
                'Pendataan statistik pariwisata dan Tingkat Penghunian Kamar (TPK) hotel.',
                'Verifikasi data survei perdagangan besar, eceran, logistik, dan jasa transportasi.',
                'Penyusunan materi rilis Berita Resmi Statistik (BRS) inflasi dan pariwisata.',
                'Analisis pergerakan harga komoditas strategis di pasar rakyat dan pasar modern.'
            ];
        } elseif (str_contains($lowerName, 'nerwilis') || str_contains($lowerName, 'neraca')) {
            return [
                'Penyusunan dan penghitungan PDRB Kota Malang menurut lapangan usaha dan pengeluaran.',
                'Analisis pertumbuhan ekonomi, struktur ekonomi daerah, dan PDRB per kapita.',
                'Kompilasi dan rekonsiliasi data sekunder dari dinas dan instansi terkait.',
                'Penyusunan draft publikasi Analisis Indikator Makroekonomi Kota Malang.',
                'Pembuatan model analisis keterkaitan antar sektor ekonomi daerah.'
            ];
        } elseif (str_contains($lowerName, 'umum') || str_contains($lowerName, 'tata usaha') || str_contains($lowerName, 'tu')) {
            return [
                'Tata kelola administrasi persuratan dinas, registrasi naskah masuk/keluar, dan arsip digital.',
                'Dukungan administrasi kepegawaian dan absensi/presensi aparatur BPS.',
                'Inventarisasi dan pengelolaan aset Barang Milik Negara (BMN).',
                'Keprotokolan rapat/acara resmi, dokumentasi kegiatan, dan kehumasan kantor.',
                'Penyusunan kelengkapan administrasi keuangan dan SPJ kegiatan dinas.'
            ];
        }

        return [
            'Pendataan dan verifikasi dokumen teknis operasional statistik.',
            'Pengolahan dan entri data menggunakan sistem aplikasi BPS.',
            'Penyusunan tabulasi berkas dan rekapitulasi data kegiatan.',
            'Dukungan administrasi dan laporan progres kerja harian.'
        ];
    }

    /**
     * Count active approved applicants on a given date.
     */
    public function getOccupiedOnDate(string $date): int
    {
        $approvedApplications = $this->applications()
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->with('members')
            ->get();

        $totalOccupied = 0;
        $countedUserIds = [];

        foreach ($approvedApplications as $app) {
            $leaderId = $app->leader_user_id;
            if ($app->members->isEmpty()) {
                $totalOccupied += 1;
                if ($leaderId) $countedUserIds[] = $leaderId;
            } else {
                foreach ($app->members as $member) {
                    $totalOccupied += 1;
                    if ($member->user_id) $countedUserIds[] = $member->user_id;
                }
            }
        }

        // Add active students from student_profiles not already counted in approved applications
        $activeStudents = $this->studentProfiles()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->when(!empty($countedUserIds), function ($q) use ($countedUserIds) {
                $q->whereNotIn('user_id', $countedUserIds);
            })
            ->count();

        $totalOccupied += $activeStudents;

        return $totalOccupied;
    }

    public function getTerisiPadaTanggal(string $date): int
    {
        return $this->getOccupiedOnDate($date);
    }

    /**
     * Check availability for a date range.
     */
    public function checkAvailability(string $startDateStr, string $endDateStr, int $headCount = 1): array
    {
        $startDate = Carbon::parse($startDateStr)->startOfDay();
        $endDate = Carbon::parse($endDateStr)->startOfDay();
        $period = CarbonPeriod::create($startDate, $endDate);
        $fullDates = [];
        $maxOccupied = 0;

        $overlapApplications = $this->applications()
            ->where('status', 'approved')
            ->where('start_date', '<=', $endDateStr)
            ->where('end_date', '>=', $startDateStr)
            ->with('members')
            ->get();

        $overlapStudents = $this->studentProfiles()
            ->whereHas('user', function ($q) {
                $q->where('role', 'siswa');
            })
            ->where('start_date', '<=', $endDateStr)
            ->where('end_date', '>=', $startDateStr)
            ->get();

        foreach ($period as $dt) {
            $dateStr = $dt->format('Y-m-d');
            $occupiedToday = 0;
            $countedUserIds = [];

            foreach ($overlapApplications as $app) {
                $rawStart = $app->getRawOriginal('start_date') ?? $app->start_date;
                $rawEnd = $app->getRawOriginal('end_date') ?? $app->end_date;
                $appStart = Carbon::parse($rawStart)->format('Y-m-d');
                $appEnd = Carbon::parse($rawEnd)->format('Y-m-d');
                $leaderId = $app->leader_user_id;

                if ($appStart <= $dateStr && $appEnd >= $dateStr) {
                    if ($app->members->isEmpty()) {
                        $occupiedToday += 1;
                        if ($leaderId) $countedUserIds[] = $leaderId;
                    } else {
                        foreach ($app->members as $member) {
                            $occupiedToday += 1;
                            if ($member->user_id) $countedUserIds[] = $member->user_id;
                        }
                    }
                }
            }

            foreach ($overlapStudents as $student) {
                $rawStart = $student->getRawOriginal('start_date') ?? $student->start_date;
                $rawEnd = $student->getRawOriginal('end_date') ?? $student->end_date;
                $sStart = Carbon::parse($rawStart)->format('Y-m-d');
                $sEnd = Carbon::parse($rawEnd)->format('Y-m-d');

                if ($sStart <= $dateStr && $sEnd >= $dateStr) {
                    if (!in_array($student->user_id, $countedUserIds)) {
                        $occupiedToday += 1;
                        $countedUserIds[] = $student->user_id;
                    }
                }
            }

            if ($occupiedToday > $maxOccupied) {
                $maxOccupied = $occupiedToday;
            }

            if (($occupiedToday + $headCount) > (int)$this->quota) {
                $fullDates[] = [
                    'tanggal' => $dateStr,
                    'tanggal_format' => $dt->translatedFormat('d M Y'),
                    'terisi' => $occupiedToday,
                    'sisa' => max(0, (int)$this->quota - $occupiedToday),
                ];
            }
        }

        $monthsDiff = max(4, $startDate->diffInMonths($endDate) + 1);
        $monthlyBreakdown = $this->getMonthlyAvailability(
            $startDate->format('Y-m'),
            min(6, $monthsDiff),
            $headCount
        );

        return [
            'available' => empty($fullDates),
            'tersedia' => empty($fullDates),
            'division_quota' => (int)$this->quota,
            'kuota_divisi' => (int)$this->quota,
            'max_occupied' => $maxOccupied,
            'max_terisi' => $maxOccupied,
            'min_remaining_quota' => max(0, (int)$this->quota - $maxOccupied),
            'sisa_kuota_minimal' => max(0, (int)$this->quota - $maxOccupied),
            'full_dates' => $fullDates,
            'tanggal_penuh' => $fullDates,
            'monthly_breakdown' => $monthlyBreakdown,
            'breakdown_bulanan' => $monthlyBreakdown,
        ];
    }

    public function cekKetersediaan(string $tanggalMulai, string $tanggalSelesai, int $jumlahOrang = 1): array
    {
        return $this->checkAvailability($tanggalMulai, $tanggalSelesai, $jumlahOrang);
    }

    public function getMonthlyAvailability(string $startMonth, int $totalMonths = 4, int $headCount = 1): array
    {
        $startDate = Carbon::parse($startMonth . '-01')->startOfMonth();
        $monthlyList = [];

        for ($i = 0; $i < $totalMonths; $i++) {
            $currentMonth = (clone $startDate)->addMonths($i);
            $monthStart = (clone $currentMonth)->startOfMonth()->format('Y-m-d');
            $monthEnd = (clone $currentMonth)->endOfMonth()->format('Y-m-d');

            $period = CarbonPeriod::create($monthStart, $monthEnd);
            $maxOccupiedMonth = 0;
            $fullDatesMonth = [];

            $overlapApps = $this->applications()
                ->where('status', 'approved')
                ->where('start_date', '<=', $monthEnd)
                ->where('end_date', '>=', $monthStart)
                ->with('members')
                ->get();

            $overlapStudents = $this->studentProfiles()
                ->whereHas('user', function ($q) {
                    $q->where('role', 'siswa');
                })
                ->where('start_date', '<=', $monthEnd)
                ->where('end_date', '>=', $monthStart)
                ->get();

            foreach ($period as $dt) {
                $dateStr = $dt->format('Y-m-d');
                $occupied = 0;
                $countedIds = [];

                foreach ($overlapApps as $app) {
                    $rawStart = $app->getRawOriginal('start_date') ?? $app->start_date;
                    $rawEnd = $app->getRawOriginal('end_date') ?? $app->end_date;
                    $pStart = Carbon::parse($rawStart)->format('Y-m-d');
                    $pEnd = Carbon::parse($rawEnd)->format('Y-m-d');
                    $leaderId = $app->leader_user_id;

                    if ($pStart <= $dateStr && $pEnd >= $dateStr) {
                        if ($app->members->isEmpty()) {
                            $occupied += 1;
                            if ($leaderId) $countedIds[] = $leaderId;
                        } else {
                            foreach ($app->members as $member) {
                                $occupied += 1;
                                if ($member->user_id) $countedIds[] = $member->user_id;
                            }
                        }
                    }
                }

                foreach ($overlapStudents as $student) {
                    $rawStart = $student->getRawOriginal('start_date') ?? $student->start_date;
                    $rawEnd = $student->getRawOriginal('end_date') ?? $student->end_date;
                    $sStart = Carbon::parse($rawStart)->format('Y-m-d');
                    $sEnd = Carbon::parse($rawEnd)->format('Y-m-d');

                    if ($sStart <= $dateStr && $sEnd >= $dateStr) {
                        if (!in_array($student->user_id, $countedIds)) {
                            $occupied += 1;
                            $countedIds[] = $student->user_id;
                        }
                    }
                }

                if ($occupied > $maxOccupiedMonth) {
                    $maxOccupiedMonth = $occupied;
                }

                if (($occupied + $headCount) > (int)$this->quota) {
                    $fullDatesMonth[] = $dt->format('d');
                }
            }

            $minRemaining = max(0, (int)$this->quota - $maxOccupiedMonth);

            $monthlyList[] = [
                'month_key' => $currentMonth->format('Y-m'),
                'bulan_key' => $currentMonth->format('Y-m'),
                'month_label' => $currentMonth->translatedFormat('F Y'),
                'bulan_label' => $currentMonth->translatedFormat('F Y'),
                'quota' => (int)$this->quota,
                'kuota' => (int)$this->quota,
                'max_occupied' => $maxOccupiedMonth,
                'max_terisi' => $maxOccupiedMonth,
                'min_remaining' => $minRemaining,
                'sisa_kursi_terkecil' => $minRemaining,
                'is_full' => $minRemaining <= 0,
                'is_penuh' => $minRemaining <= 0,
                'full_date_numbers' => $fullDatesMonth,
                'tanggal_penuh_nomor' => $fullDatesMonth,
            ];
        }

        return $monthlyList;
    }

    public function getKetersediaanBulanan(string $startMonth, int $totalMonths = 4, int $jumlahOrang = 1): array
    {
        return $this->getMonthlyAvailability($startMonth, $totalMonths, $jumlahOrang);
    }

    /**
     * Kalender Kuota Divisi per hari dalam bulan tertentu (AJAX endpoint / UI Kalender).
     */
    public function getQuotaCalendar(int $year, int $month, ?int $excludeId = null): array
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();
        $monthStartStr = $startOfMonth->format('Y-m-d');
        $monthEndStr = $endOfMonth->format('Y-m-d');

        $quota = (int) $this->quota;

        // Ambil pendaftaran approved yang overlap dengan bulan ini
        $applicationsQuery = $this->applications()
            ->where('status', 'approved')
            ->where('start_date', '<=', $monthEndStr)
            ->where('end_date', '>=', $monthStartStr)
            ->with(['members', 'leader']);

        if ($excludeId) {
            $applicationsQuery->where('id', '!=', $excludeId);
        }

        $overlapApplications = $applicationsQuery->get();

        // Ambil profil siswa aktif yang overlap dengan bulan ini
        $overlapStudents = $this->studentProfiles()
            ->whereHas('user', function ($q) {
                $q->where('role', 'siswa');
            })
            ->where('start_date', '<=', $monthEndStr)
            ->where('end_date', '>=', $monthStartStr)
            ->with('user')
            ->get();

        $days = [];
        $totalDays = (int) $endOfMonth->day;

        for ($d = 1; $d <= $totalDays; $d++) {
            $currentDate = Carbon::createFromDate($year, $month, $d)->startOfDay();
            $dateStr = $currentDate->format('Y-m-d');
            $occupiedToday = 0;
            $countedUserIds = [];
            $pesertas = [];

            foreach ($overlapApplications as $app) {
                $rawStart = $app->getRawOriginal('start_date') ?? $app->start_date;
                $rawEnd = $app->getRawOriginal('end_date') ?? $app->end_date;
                $appStart = Carbon::parse($rawStart)->format('Y-m-d');
                $appEnd = Carbon::parse($rawEnd)->format('Y-m-d');
                $leaderId = $app->leader_user_id;

                if ($appStart <= $dateStr && $appEnd >= $dateStr) {
                    $periodeFormatted = Carbon::parse($rawStart)->format('d/m/Y') . ' - ' . Carbon::parse($rawEnd)->format('d/m/Y');
                    if ($app->members->isEmpty()) {
                        $occupiedToday += 1;
                        if ($leaderId) {
                            $countedUserIds[] = $leaderId;
                        }
                        $pesertas[] = [
                            'nama' => $app->leader?->name ?? 'Peserta Magang',
                            'asal' => $app->institution ?? '-',
                            'jurusan' => $app->major ?? '-',
                            'kelompok' => $app->group_name,
                            'periode' => $periodeFormatted,
                        ];
                    } else {
                        foreach ($app->members as $member) {
                            $occupiedToday += 1;
                            if ($member->user_id) {
                                $countedUserIds[] = $member->user_id;
                            }
                            $pesertas[] = [
                                'nama' => $member->full_name ?? ($member->user?->name ?? 'Peserta Magang'),
                                'asal' => $member->institution ?? ($app->institution ?? '-'),
                                'jurusan' => $member->major ?? ($app->major ?? '-'),
                                'kelompok' => $app->group_name,
                                'periode' => $periodeFormatted,
                            ];
                        }
                    }
                }
            }

            foreach ($overlapStudents as $student) {
                $rawStart = $student->getRawOriginal('start_date') ?? $student->start_date;
                $rawEnd = $student->getRawOriginal('end_date') ?? $student->end_date;
                $sStart = Carbon::parse($rawStart)->format('Y-m-d');
                $sEnd = Carbon::parse($rawEnd)->format('Y-m-d');

                if ($sStart <= $dateStr && $sEnd >= $dateStr) {
                    if (!in_array($student->user_id, $countedUserIds)) {
                        $occupiedToday += 1;
                        if ($student->user_id) {
                            $countedUserIds[] = $student->user_id;
                        }
                        $periodeFormatted = Carbon::parse($rawStart)->format('d/m/Y') . ' - ' . Carbon::parse($rawEnd)->format('d/m/Y');
                        $pesertas[] = [
                            'nama' => $student->full_name ?? ($student->user?->name ?? 'Siswa Magang'),
                            'asal' => $student->institution ?? '-',
                            'jurusan' => '-',
                            'kelompok' => null,
                            'periode' => $periodeFormatted,
                        ];
                    }
                }
            }

            $sisa = max(0, $quota - $occupiedToday);

            $status = 'tersedia';
            if ($occupiedToday > $quota) {
                $status = 'over_quota';
            } elseif ($occupiedToday >= $quota) {
                $status = 'penuh';
            } elseif ($sisa <= 2 && $quota > 2) {
                $status = 'terbatas';
            }

            $days[] = [
                'tanggal' => $dateStr,
                'hari' => $d,
                'day_of_week' => $currentDate->dayOfWeek,
                'is_today' => $currentDate->isToday(),
                'kuota' => $quota,
                'terisi' => $occupiedToday,
                'sisa' => $sisa,
                'status' => $status,
                'pesertas' => $pesertas,
            ];
        }

        return [
            'year' => $year,
            'month' => $month,
            'month_name' => $startOfMonth->locale('id')->translatedFormat('F Y'),
            'first_day_of_week' => $startOfMonth->dayOfWeek,
            'total_days' => $totalDays,
            'divisi' => [
                'id' => $this->id,
                'nama_divisi' => $this->name,
                'name' => $this->name,
                'kuota' => $quota,
                'quota' => $quota,
            ],
            'days' => $days,
        ];
    }

    public function getKalenderKuota(int $year, int $month, ?int $excludeId = null): array
    {
        return $this->getQuotaCalendar($year, $month, $excludeId);
    }
}

