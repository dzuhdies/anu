<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'nama' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'nullable|integer',
        ]);

        $name = $request->name ?? $request->nama;
        if (!$name) {
            return back()->with('error', 'Nama gedung wajib diisi');
        }

        Building::create([
            'name' => $name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => $request->radius_meter ?? 100,
        ]);

        return back()->with('success', 'Gedung berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'nama' => 'nullable|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'nullable|integer',
        ]);

        $building = Building::findOrFail($id);
        $name = $request->name ?? $request->nama ?? $building->name;

        $building->update([
            'name' => $name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => $request->radius_meter ?? $building->radius_meter,
        ]);

        return back()->with('success', 'Gedung berhasil diperbarui');
    }

    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();
        return back()->with('success', 'Gedung berhasil dihapus');
    }
}
