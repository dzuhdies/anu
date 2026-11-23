<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Division;
use App\Models\Building;
use App\Models\Setting;
use App\Models\LeaveType;
use App\Models\InternshipApplication;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = LogActivity::query();

        // Search across action, keterangan, user, and IP
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('user', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $query->where('user', $user->username);
            }
        }

        $perPage = (int) $request->get('per_page', 25);
        if (!in_array($perPage, [15, 25, 50, 100])) {
            $perPage = 25;
        }

        $logs = $query->orderBy('created_at', 'desc')
                      ->paginate($perPage)
                      ->withQueryString();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $logs
            ]);
        }

        // Summary stats for KPI cards
        $stats = [
            'total'      => LogActivity::count(),
            'today'      => LogActivity::whereDate('created_at', today())->count(),
            'berhasil'   => LogActivity::where('status', 'berhasil')->count(),
            'gagal'      => LogActivity::where('status', 'gagal')->count(),
            'salah_isi'  => LogActivity::where('status', 'salah_isi')->count(),
            'siswa'      => LogActivity::where('role', 'siswa')->count(),
            'staff'      => LogActivity::whereIn('role', ['admin', 'pegawai'])->count(),
            'unique_ips' => LogActivity::distinct('ip_address')->count('ip_address'),
        ];

        // Sidebar counts matching admin dashboard
        $pendingCount   = InternshipApplication::where('status', 'pending')->count();
        $siswaCount     = User::where('role', 'siswa')->count();
        $pegawaiCount   = User::where('role', 'pegawai')->count();
        $adminCount     = User::where('role', 'admin')->count();
        $divisiCount    = Division::count();
        $gedungCount    = Building::count();
        $jamTelat       = Setting::where('key', 'jam_telat')->value('value') ?? '08:16';
        $jenisIzinCount = LeaveType::count();

        $users = User::select('id', 'username', 'role')->orderBy('username')->get();

        return view('admin.log', compact(
            'logs',
            'users',
            'stats',
            'pendingCount',
            'siswaCount',
            'pegawaiCount',
            'adminCount',
            'divisiCount',
            'gedungCount',
            'jamTelat',
            'jenisIzinCount'
        ));
    }
}
