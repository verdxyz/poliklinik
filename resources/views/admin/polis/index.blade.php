<x-layouts.app title="Data Poli">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Poli</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar poli klinik.</p>
        </div>
        <a href="{{ route('polis.create') }}" class="btn btn-primary rounded-lg">
            <i class="fas fa-plus"></i> Tambah Poli
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="table">
            <thead class="bg-slate-50 text-slate-600">
                <tr><th>Nama Poli</th><th>Keterangan</th><th class="text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($polis as $poli)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $poli->nama_poli }}</td>
                        <td class="max-w-xl whitespace-normal text-slate-600">{{ $poli->keterangan ?: '—' }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('polis.edit', $poli) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('polis.destroy', $poli) }}" onsubmit="return confirm('Hapus poli ini? Dokter yang terhubung akan kehilangan poli.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error text-white" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-10 text-center text-slate-500">Belum ada data poli.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
