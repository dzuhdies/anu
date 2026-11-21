<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Settings
        if (DB::table('settings')->where('key', 'jam_telat')->doesntExist()) {
            DB::table('settings')->insert([
                'key' => 'jam_telat',
                'value' => '08:16:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Default Building (BPS)
        if (DB::table('buildings')->count() === 0) {
            DB::table('buildings')->insert([
                'name' => 'Kantor BPS Kota Malang',
                'latitude' => -7.94825900,
                'longitude' => 112.62884700,
                'radius_meter' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Default Leave Types
        $leaveTypes = [
            'Sakit',
            'Izin Kampus / Sekolah',
            'Keperluan Mendesak',
        ];
        foreach ($leaveTypes as $name) {
            if (DB::table('leave_types')->where('name', $name)->doesntExist()) {
                DB::table('leave_types')->insert([
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 4. Default Divisions
        $divisions = [
            ['name' => 'IPDS (Integrasi Pengolahan dan Diseminasi Statistik)', 'quota' => 5],
            ['name' => 'Statistik Sosial', 'quota' => 5],
            ['name' => 'Statistik Produksi', 'quota' => 5],
            ['name' => 'Statistik Distribusi', 'quota' => 5],
            ['name' => 'Nerwilis (Neraca Wilayah dan Analisis Statistik)', 'quota' => 5],
            ['name' => 'Tata Usaha / Umum', 'quota' => 5],
        ];
        foreach ($divisions as $div) {
            if (DB::table('divisions')->where('name', $div['name'])->doesntExist()) {
                DB::table('divisions')->insert([
                    'name' => $div['name'],
                    'quota' => $div['quota'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. Default Admin User
        if (DB::table('users')->where('username', 'admin')->doesntExist()) {
            DB::table('users')->insert([
                'username' => 'admin',
                'email' => 'admin@bps.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
