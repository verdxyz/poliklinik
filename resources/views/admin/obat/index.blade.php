<x-layouts.app title="Data Obat">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Obat</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola nama, kemasan, dan harga obat klinik.</p>
        </div>
        <a href="{{ route('obat.create') }}" class="btn btn-primary rounded-lg"><i class="fas fa-plus"></i> Tambah Obat</a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="table">
            <thead class="bg-slate-50 text-slate-600">
                <tr><th>Nama Obat</th><th>Kemasan</th><th>Harga</th><th class="text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($obats as $obat)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->kemasan }}</td>
                        <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('obat.edit', $obat) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('obat.destroy', $obat) }}" onsubmit="return confirm('Hapus data obat ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error text-white" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-10 text-center text-slate-500">Belum ada data obat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
