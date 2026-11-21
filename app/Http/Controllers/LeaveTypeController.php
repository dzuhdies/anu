<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        $jenisIzin = $leaveTypes;
        return view('admin.jenis_izin.index', compact('leaveTypes', 'jenisIzin'));
    }

    public function store(Request $request)
    {
        $nameField = $request->filled('name') ? 'name' : 'nama';
        $validator = Validator::make($request->all(), [
            $nameField => 'required|string|max:255|unique:leave_types,name'
        ], [
            $nameField . '.required' => 'Nama jenis izin wajib diisi.',
            $nameField . '.unique' => 'Nama jenis izin sudah ada.'
        ]);

        if ($validator->fails()) {
            LogHelper::invalid($request, 'Tambah Jenis Izin (Salah Isi)', $validator->errors());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $name = $request->input($nameField);
        $leaveType = LeaveType::create([
            'name' => $name,
        ]);
        $logMessage = 'Jenis izin "' . $leaveType->name . '" berhasil ditambahkan.';
        LogHelper::success($request, 'Tambah Jenis Izin Berhasil', $logMessage);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $logMessage]);
        }

        return back()->with('success', 'Jenis izin ditambahkan.');
    }

    public function destroy(Request $request, $id)
    {
        $leaveType = LeaveType::withCount('attendances')->findOrFail($id);

        if ($leaveType->attendances_count > 0) {
            $logMessage = 'Gagal menghapus jenis izin "' . $leaveType->name . '" karena sudah digunakan pada riwayat presensi izin.';
            LogHelper::fail($request, 'Hapus Jenis Izin Ditolak', $logMessage);

            return back()->with('error', 'Jenis izin "' . $leaveType->name . '" tidak dapat dihapus karena sudah digunakan.');
        }

        $deletedName = $leaveType->name;
        $leaveType->delete();

        $logMessage = 'Jenis izin "' . $deletedName . '" berhasil dihapus.';
        LogHelper::success($request, 'Hapus Jenis Izin Berhasil', $logMessage);

        return back()->with('success', 'Jenis izin berhasil dihapus.');
    }
}
