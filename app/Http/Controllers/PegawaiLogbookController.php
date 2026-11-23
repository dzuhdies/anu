<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Logbook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiLogbookController extends Controller
{
    /**
     * Review logbook siswa oleh pegawai.
     */
    public function index(Request $request)
    {
        return redirect()->route('pegawai.absensi', array_filter([
            'tab' => 'logbook',
            'status' => $request->query('status'),
            'tanggal' => $request->query('tanggal'),
            'q' => $request->query('q'),
            'page' => $request->query('page'),
        ]));
    }

    public function approve(Request $request, Logbook $logbook)
    {
        if ($logbook->status === Logbook::STATUS_APPROVED) {
            return back()->with('info', 'Logbook ini sudah disetujui sebelumnya.');
        }

        $logbook->update([
            'status' => Logbook::STATUS_APPROVED,
            'review_note' => null,
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => now(),
        ]);

        LogHelper::success($request, 'Setujui Logbook Berhasil', "Pegawai menyetujui logbook #{$logbook->id} ({$logbook->student->full_name}).");

        return back()->with('success', 'Logbook berhasil disetujui.');
    }

    public function reject(Request $request, Logbook $logbook)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'review_note' => 'required|string|min:3|max:2000',
        ], [
            'review_note.required' => 'Alasan penolakan wajib diisi.',
            'review_note.min' => 'Alasan penolakan terlalu pendek, minimal 3 karakter.',
            'review_note.max' => 'Alasan penolakan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tolak Logbook (Salah Isi)', $validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $logbook->update([
            'status' => Logbook::STATUS_REJECTED,
            'review_note' => trim($request->review_note),
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => now(),
        ]);

        LogHelper::success($request, 'Tolak Logbook Berhasil', "Pegawai menolak logbook #{$logbook->id} ({$logbook->student->full_name}).");

        return back()->with('success', 'Logbook berhasil ditolak. Siswa dapat memperbaikinya selama masih dalam masa tenggang.');
    }
}
