<x-layouts.app title="Data Dokter">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Dokter</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data dan penempatan poli dokter.</p>
        </div>
        <a href="{{ route('dokter.create') }}" class="btn btn-primary rounded-lg"><i class="fas fa-plus"></i> Tambah Dokter</a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="table">
            <thead class="bg-slate-50 text-slate-600">
                <tr><th>Nama Dokter</th><th>Email</th><th>No. KTP</th><th>No. HP</th><th>Alamat</th><th>Poli</th><th class="text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($dokters as $dokter)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $dokter->nama }}</td>
                        <td>{{ $dokter->email }}</td>
                        <td>{{ $dokter->no_ktp }}</td>
                        <td>{{ $dokter->no_hp }}</td>
                        <td class="max-w-xs whitespace-normal">{{ $dokter->alamat }}</td>
                        <td>{{ $dokter->poli?->nama_poli ?? 'Belum ditentukan' }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('dokter.edit', $dokter) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('dokter.destroy', $dokter) }}" onsubmit="return confirm('Hapus data dokter ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error text-white" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-slate-500">Belum ada data dokter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
