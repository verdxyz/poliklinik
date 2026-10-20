<x-layouts.app title="Tambah Jadwal Periksa">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-circle btn-ghost btn-sm"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Jadwal Periksa</h1>
    </div>
    <div class="card max-w-3xl border border-slate-200 bg-white shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('jadwal-periksa.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="hari" class="label font-semibold">Hari <span class="text-error">*</span></label>
                <select id="hari" name="hari" required class="select select-bordered w-full @error('hari') select-error @enderror">
                    <option value="">Pilih hari</option>
                    @foreach($haris as $hari)<option value="{{ $hari }}" @selected(old('hari') === $hari)>{{ $hari }}</option>@endforeach
                </select>
                @error('hari')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="jam_mulai" class="label font-semibold">Jam Mulai <span class="text-error">*</span></label>
                <input id="jam_mulai" type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required class="input input-bordered w-full @error('jam_mulai') input-error @enderror">
                @error('jam_mulai')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="jam_selesai" class="label font-semibold">Jam Selesai <span class="text-error">*</span></label>
                <input id="jam_selesai" type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required class="input input-bordered w-full @error('jam_selesai') input-error @enderror">
                @error('jam_selesai')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="label font-semibold">Status <span class="text-error">*</span></label>
                <select id="status" name="status" required class="select select-bordered w-full @error('status') select-error @enderror">
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-ghost" href="{{ route('jadwal-periksa.index') }}">Batal</a>
            </div>
        </form>
    </div></div>
</x-layouts.app>
