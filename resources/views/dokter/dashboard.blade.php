<x-layouts.app title="Dokter Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Halo, {{ auth()->user()->nama ?? 'Dokter' }}</h1>
        <p class="mt-1 text-sm text-slate-500">Pasien yang mendaftar poli akan muncul pada jadwal Anda.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Jadwal Periksa</h2>
            </div>
            <table class="table">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Pasien Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $jadwal)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $jadwal->hari }}</td>
                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                            <td>{{ $jadwal->daftar_polis_count }} pasien</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center text-slate-500">Belum ada jadwal periksa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Pasien Terdaftar</h2>
            </div>
            <table class="table">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th>Pasien</th>
                        <th>Jadwal</th>
                        <th>Antrian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pasienTerdaftar as $daftar)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $daftar->pasien?->nama ?? '-' }}</td>
                            <td>
                                {{ $daftar->jadwalPeriksa?->hari }}
                                {{ substr($daftar->jadwalPeriksa?->jam_mulai ?? '', 0, 5) }}
                                -
                                {{ substr($daftar->jadwalPeriksa?->jam_selesai ?? '', 0, 5) }}
                            </td>
                            <td>{{ $daftar->no_antrian }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center text-slate-500">Belum ada pasien yang mendaftar ke jadwal Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
