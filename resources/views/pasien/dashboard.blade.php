<x-layouts.app title="Pasien Dashboard">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ $user->nama ?? 'Pasien' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Lihat pendaftaran poli Anda, atau daftar ke jadwal dokter.</p>
        </div>

        {{-- Tombol Daftar Poli --}}
        <a href="{{ route('pasien.daftar') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition animate-pulse">
            <i class="fas fa-house-medical"></i>
            + Daftar Poli
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="table">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Jadwal</th>
                    <th>No. Antrian</th>
                    <th>Keluhan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftars as $daftar)
                    <tr>
                        <td class="font-semibold text-slate-800">
                            {{ $daftar->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}
                        </td>
                        <td>{{ $daftar->jadwalPeriksa?->dokter?->nama ?? '-' }}</td>
                        <td>
                            {{ $daftar->jadwalPeriksa?->hari }}
                            {{ substr($daftar->jadwalPeriksa?->jam_mulai ?? '', 0, 5) }}
                            -
                            {{ substr($daftar->jadwalPeriksa?->jam_selesai ?? '', 0, 5) }}
                        </td>
                        <td>{{ $daftar->no_antrian }}</td>
                        <td class="text-slate-500">{{ $daftar->keluhan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-clipboard-list text-4xl text-slate-300"></i>
                                <span>Belum ada pendaftaran poli.</span>
                                <a href="{{ route('pasien.daftar') }}"
                                   class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition">
                                    <i class="fas fa-house-medical"></i>
                                    Daftar Poli Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
