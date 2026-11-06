<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $daftars = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $user->id)
            ->latest()
            ->get();

        return view('pasien.dashboard', compact('user', 'daftars'));
    }

    public function get()
    {
        $user = Auth::user();
        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')
            ->where('status', 'aktif')
            ->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
        ]);

        $jadwal = JadwalPeriksa::with('dokter')->findOrFail($request->id_jadwal);

        if ($jadwal->status !== 'aktif' || (int) $jadwal->dokter?->id_poli !== (int) $request->id_poli) {
            return back()
                ->withErrors(['id_jadwal' => 'Jadwal tidak valid untuk poli yang dipilih.'])
                ->withInput();
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $jadwal->id)->count();

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $jadwal->id,
            'keluhan' => $request->keluhan,
            'no_antrian' => $jumlahSudahDaftar + 1,
        ]);

        return redirect()
            ->route('pasien.dashboard')
            ->with('success', 'Berhasil mendaftar ke poli. Jadwal Anda sudah masuk ke akun dokter.');
    }
}

