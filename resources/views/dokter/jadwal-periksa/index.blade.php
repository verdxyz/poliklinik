<x-layouts.app title="Jadwal Periksa">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Jadwal Periksa</h1>
            <p class="mt-1 text-sm text-slate-500">Atur jadwal praktik Anda.</p>
        </div>
        <a href="{{ route('jadwal-periksa.create') }}" class="btn btn-primary rounded-lg"><i class="fas fa-plus"></i> Tambah Jadwal</a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="table">
            <thead class="bg-slate-50 text-slate-600">
                <tr><th>Hari</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Status</th><th class="text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $jadwal->hari }}</td>
                        <td>{{ substr($jadwal->jam_mulai, 0, 5) }}</td>
                        <td>{{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                        <td><span class="badge {{ $jadwal->status === 'aktif' ? 'badge-success' : 'badge-ghost' }}">{{ ucfirst($jadwal->status) }}</span></td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('jadwal-periksa.edit', $jadwal) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('jadwal-periksa.destroy', $jadwal) }}" onsubmit="return confirm('Hapus jadwal periksa ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error text-white" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-slate-500">Belum ada jadwal periksa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
