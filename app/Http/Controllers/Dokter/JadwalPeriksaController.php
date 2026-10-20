<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JadwalPeriksaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index()
    {
        $jadwals = Auth::user()->jadwalPeriksas()->orderByRaw(
            "FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')"
        )->orderBy('jam_mulai')->get();

        return view('dokter.jadwal-periksa.index', compact('jadwals'));
    }

    public function create()
    {
        $haris = self::HARI;

        return view('dokter.jadwal-periksa.create', compact('haris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => ['required', Rule::in(self::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        Auth::user()->jadwalPeriksas()->create($validated);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil ditambahkan.');
    }

    public function edit(JadwalPeriksa $jadwal_periksa)
    {
        $this->ensureOwnedByCurrentDoctor($jadwal_periksa);
        $haris = self::HARI;

        return view('dokter.jadwal-periksa.edit', [
            'jadwal' => $jadwal_periksa,
            'haris' => $haris,
        ]);
    }

    public function update(Request $request, JadwalPeriksa $jadwal_periksa)
    {
        $this->ensureOwnedByCurrentDoctor($jadwal_periksa);

        $validated = $request->validate([
            'hari' => ['required', Rule::in(self::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $jadwal_periksa->update($validated);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(JadwalPeriksa $jadwal_periksa)
    {
        $this->ensureOwnedByCurrentDoctor($jadwal_periksa);
        $jadwal_periksa->delete();

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil dihapus.');
    }

    private function ensureOwnedByCurrentDoctor(JadwalPeriksa $jadwal): void
    {
        abort_unless((int) $jadwal->id_dokter === (int) Auth::id(), 404);
    }
}
