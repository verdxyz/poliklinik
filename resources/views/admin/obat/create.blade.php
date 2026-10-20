<x-layouts.app title="Tambah Obat">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('obat.index') }}" class="btn btn-circle btn-ghost btn-sm"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Obat</h1>
    </div>
    <div class="card max-w-3xl border border-slate-200 bg-white shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('obat.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="nama_obat" class="label font-semibold">Nama Obat <span class="text-error">*</span></label>
                <input id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" required class="input input-bordered w-full @error('nama_obat') input-error @enderror" placeholder="Contoh: Paracetamol">
                @error('nama_obat')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kemasan" class="label font-semibold">Kemasan <span class="text-error">*</span></label>
                <input id="kemasan" name="kemasan" value="{{ old('kemasan') }}" maxlength="35" required class="input input-bordered w-full @error('kemasan') input-error @enderror" placeholder="Contoh: Tablet 500 mg">
                @error('kemasan')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="harga" class="label font-semibold">Harga (Rp) <span class="text-error">*</span></label>
                <input id="harga" type="number" name="harga" value="{{ old('harga') }}" min="0" step="1" required class="input input-bordered w-full @error('harga') input-error @enderror" placeholder="Masukkan harga">
                @error('harga')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-ghost" href="{{ route('obat.index') }}">Batal</a>
            </div>
        </form>
    </div></div>
</x-layouts.app>
