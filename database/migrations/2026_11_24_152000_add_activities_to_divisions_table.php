<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('divisions', 'activities')) {
            Schema::table('divisions', function (Blueprint $table) {
                $table->json('activities')->nullable()->after('description');
            });
        }

        // Seed default activities for existing divisions
        $templates = [
            'ipds' => [
                'Pengembangan dan pemeliharaan website/sistem informasi internal BPS.',
                'Perancangan visualisasi data interaktif, dashboard analitik, dan infografis publikasi.',
                'Pengelolaan basis data hasil survei dan integrasi data sensus statistik.',
                'Pelayanan konsultasi data statistik di Pelayanan Statistik Terpadu (PST) dan Pojok Statistik.',
                'Dukungan jaringan, pemeliharaan perangkat keras, dan keamanan sistem informasi kantor.'
            ],
            'sosial' => [
                'Validasi, konsistensi, dan entri dokumen survei sosial ekonomi (Susenas dan Sakernas).',
                'Penyusunan tabulasi data kemiskinan, ketimpangan pengeluaran (Gini Ratio), dan IPM.',
                'Pengolahan indikator ketenagakerjaan, upah buruh, serta statistik gender dan pendidikan.',
                'Penyusunan draft publikasi profil statistik kependudukan dan sosial Kota Malang.',
                'Pemeriksaan kelengkapan berkas kuesioner pendataan lapangan.'
            ],
            'produksi' => [
                'Pemutakhiran direktori perusahaan industri manufaktur skala besar, sedang, dan mikro/kecil.',
                'Pengolahan data survei statistik pertanian tanaman pangan, hortikultura, dan peternakan.',
                'Pencatatan dan verifikasi data survei perusahaan konstruksi dan bahan galian.',
                'Penyusunan laporan indeks produksi industri manufaktur dan profil komoditas daerah.',
                'Rekapitulasi dan pelaporan berkas bulanan data produksi unggulan daerah.'
            ],
            'distribusi' => [
                'Pencatatan dan pengolahan Survei Harga Konsumen (SHK) untuk perhitungan inflasi Kota Malang.',
                'Pendataan statistik pariwisata dan Tingkat Penghunian Kamar (TPK) hotel.',
                'Verifikasi data survei perdagangan besar, eceran, logistik, dan jasa transportasi.',
                'Penyusunan materi rilis Berita Resmi Statistik (BRS) inflasi dan pariwisata.',
                'Analisis pergerakan harga komoditas strategis di pasar rakyat dan pasar modern.'
            ],
            'nerwilis' => [
                'Penyusunan dan penghitungan PDRB Kota Malang menurut lapangan usaha dan pengeluaran.',
                'Analisis pertumbuhan ekonomi, struktur ekonomi daerah, dan PDRB per kapita.',
                'Kompilasi dan rekonsiliasi data sekunder dari dinas dan instansi terkait.',
                'Penyusunan draft publikasi Analisis Indikator Makroekonomi Kota Malang.',
                'Pembuatan model analisis keterkaitan antar sektor ekonomi daerah.'
            ],
            'umum' => [
                'Tata kelola administrasi persuratan dinas, registrasi naskah masuk/keluar, dan arsip digital.',
                'Dukungan administrasi kepegawaian dan absensi/presensi aparatur BPS.',
                'Inventarisasi dan pengelolaan aset Barang Milik Negara (BMN).',
                'Keprotokolan rapat/acara resmi, dokumentasi kegiatan, dan kehumasan kantor.',
                'Penyusunan kelengkapan administrasi keuangan dan SPJ kegiatan dinas.'
            ],
        ];

        $defaultTasks = [
            'Pendataan dan verifikasi dokumen teknis operasional statistik.',
            'Pengolahan dan entri data menggunakan sistem aplikasi BPS.',
            'Penyusunan tabulasi berkas dan rekapitulasi data kegiatan.',
            'Dukungan administrasi dan laporan progres kerja harian.'
        ];

        $divisions = DB::table('divisions')->get();
        foreach ($divisions as $div) {
            if (empty($div->activities)) {
                $lower = strtolower($div->name ?? '');
                $acts = $defaultTasks;
                if (str_contains($lower, 'ipds') || str_contains($lower, 'integrasi')) {
                    $acts = $templates['ipds'];
                } elseif (str_contains($lower, 'sosial')) {
                    $acts = $templates['sosial'];
                } elseif (str_contains($lower, 'produksi')) {
                    $acts = $templates['produksi'];
                } elseif (str_contains($lower, 'distribusi')) {
                    $acts = $templates['distribusi'];
                } elseif (str_contains($lower, 'nerwilis') || str_contains($lower, 'neraca')) {
                    $acts = $templates['nerwilis'];
                } elseif (str_contains($lower, 'umum') || str_contains($lower, 'tata usaha') || str_contains($lower, 'tu')) {
                    $acts = $templates['umum'];
                }
                DB::table('divisions')->where('id', $div->id)->update([
                    'activities' => json_encode($acts)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('divisions', 'activities')) {
            Schema::table('divisions', function (Blueprint $table) {
                $table->dropColumn('activities');
            });
        }
    }
};
