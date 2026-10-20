<x-layouts.app title="Edit Dokter">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('dokter.index') }}" class="btn btn-circle btn-ghost btn-sm"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-slate-800">Edit Dokter</h1>
    </div>
    <div class="card max-w-4xl border border-slate-200 bg-white shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('dokter.update', $dokter) }}" class="grid gap-5 md:grid-cols-2">
            @csrf @method('PUT')
            <div>
                <label for="nama" class="label font-semibold">Nama Dokter <span class="text-error">*</span></label>
                <input id="nama" name="nama" value="{{ old('nama', $dokter->nama) }}" required class="input input-bordered w-full @error('nama') input-error @enderror">
                @error('nama')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="label font-semibold">Email <span class="text-error">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email', $dokter->email) }}" required class="input input-bordered w-full @error('email') input-error @enderror">
                @error('email')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="no_ktp" class="label font-semibold">No. KTP <span class="text-error">*</span></label>
                <input id="no_ktp" name="no_ktp" value="{{ old('no_ktp', $dokter->no_ktp) }}" maxlength="16" required class="input input-bordered w-full @error('no_ktp') input-error @enderror">
                @error('no_ktp')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="no_hp" class="label font-semibold">No. HP <span class="text-error">*</span></label>
                <input id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" maxlength="15" required class="input input-bordered w-full @error('no_hp') input-error @enderror">
                @error('no_hp')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="id_poli" class="label font-semibold">Poli <span class="text-error">*</span></label>
                <select id="id_poli" name="id_poli" required class="select select-bordered w-full @error('id_poli') select-error @enderror">
                    <option value="">Pilih poli</option>
                    @foreach($polis as $poli)<option value="{{ $poli->id }}" @selected(old('id_poli', $dokter->id_poli) == $poli->id)>{{ $poli->nama_poli }}</option>@endforeach
                </select>
                @error('id_poli')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="label font-semibold">Password Baru</label>
                <input id="password" type="password" name="password" minlength="6" class="input input-bordered w-full @error('password') input-error @enderror" placeholder="Kosongkan jika tidak diubah">
                @error('password')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label for="alamat" class="label font-semibold">Alamat <span class="text-error">*</span></label>
                <textarea id="alamat" name="alamat" rows="3" required class="textarea textarea-bordered w-full @error('alamat') textarea-error @enderror">{{ old('alamat', $dokter->alamat) }}</textarea>
                @error('alamat')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 md:col-span-2">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a class="btn btn-ghost" href="{{ route('dokter.index') }}">Batal</a>
            </div>
        </form>
    </div></div>
</x-layouts.app>
