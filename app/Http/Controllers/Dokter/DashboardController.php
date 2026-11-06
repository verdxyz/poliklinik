<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $jadwals = JadwalPeriksa::with(['daftarPolis.pasien'])
            ->withCount('daftarPolis')
            ->where('id_dokter', $dokterId)
            ->orderByRaw(
                "CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 END"
            )
            ->orderBy('jam_mulai')
            ->get();

        $pasienTerdaftar = DaftarPoli::with(['pasien', 'jadwalPeriksa'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->latest()
            ->get();

        return view('dokter.dashboard', compact('jadwals', 'pasienTerdaftar'));
    }
}
