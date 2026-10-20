<x-layouts.app title="Edit Poli">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('polis.index') }}" class="btn btn-circle btn-ghost btn-sm"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-800">Edit Poli</h1>
    </div>
    <div class="card max-w-3xl border border-slate-200 bg-white shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('polis.update', $poli) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label for="nama_poli" class="label font-semibold">Nama Poli <span class="text-error">*</span></label>
                <input id="nama_poli" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}" maxlength="25" required class="input input-bordered w-full @error('nama_poli') input-error @enderror">
                @error('nama_poli')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="keterangan" class="label font-semibold">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4" class="textarea textarea-bordered w-full @error('keterangan') textarea-error @enderror">{{ old('keterangan', $poli->keterangan) }}</textarea>
                @error('keterangan')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a class="btn btn-ghost" href="{{ route('polis.index') }}">Batal</a>
            </div>
        </form>
    </div></div>
</x-layouts.app>
