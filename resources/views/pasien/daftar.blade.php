<x-layouts.app title="Daftar Poli">

    <div class="flex items-center justify-center px-4">
        <div class="w-full max-w-3xl">
            <div class="card bg-base-100 shadow">
                <div class="card-body">

                    <h2 class="text-2xl font-bold text-center mb-6">
                        🏥 Pendaftaran Poli
                    </h2>

                    {{-- Toast Success --}}
                    @if (session('message'))
                    <div id="toastSuccess" class="alert alert-success mb-4 shadow-lg">
                        <i class="fas fa-circle-check"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                    @endif

                    {{-- Error Validasi --}}
                    @if ($errors->any())
                    <div class="alert alert-error mb-4">
                        <i class="fas fa-circle-xmark"></i>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                        @csrf

                        {{-- Nomor RM --}}
                        <div class="mb-4">
                            <label class="font-semibold block mb-1">
                                Nomor Rekam Medis
                            </label>
                            <input type="text" value="{{ $user->no_rm ?? '-' }}"
                                class="w-full border-2 rounded-lg p-2 bg-gray-100" disabled>
                        </div>

                        {{-- Pilih Poli --}}
                        <div class="mb-4">
                            <label class="font-semibold block mb-1">
                                Pilih Poli <span class="text-red-500">*</span>
                            </label>
                            <select name="id_poli" id="poliSelect"
                                class="w-full border-2 rounded-lg p-2 @error('id_poli') border-red-500 @enderror">
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $poli)
                                <option value="{{ $poli->id }}"
                                    {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_poli')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilih Jadwal --}}
                        <div class="mb-4">
                            <label class="font-semibold block mb-1">
                                Pilih Jadwal Periksa <span class="text-red-500">*</span>
                            </label>
                            <select name="id_jadwal" id="jadwalSelect"
                                class="w-full border-2 rounded-lg p-2 @error('id_jadwal') border-red-500 @enderror">
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    data-poli="{{ $jadwal->dokter->id_poli }}"
                                    {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->hari }}
                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    (Dr. {{ $jadwal->dokter->nama ?? '--' }})
                                </option>
                                @endforeach
                            </select>
                            @error('id_jadwal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Keluhan --}}
                        <div class="mb-6">
                            <label class="font-semibold block mb-1">
                                Keluhan
                            </label>
                            <textarea name="keluhan" rows="3"
                                class="w-full border-2 rounded-lg p-2"
                                placeholder="Tulis keluhan anda... (opsional)">{{ old('keluhan') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                style="width:100%; padding:14px; background-color:#f59e0b; color:#fff; font-size:18px; font-weight:700; border:none; border-radius:10px; cursor:pointer; box-shadow:0 4px 14px rgba(245,158,11,0.4);">
                                ✅ Daftar Poli
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const poliSelect = document.getElementById("poliSelect")
            const jadwalSelect = document.getElementById("jadwalSelect")
            const allJadwalOptions = Array.from(jadwalSelect.querySelectorAll("option[value]"))

            function filterJadwal(poliId) {
                allJadwalOptions.forEach(option => {
                    if (!option.value) return
                    if (!poliId || option.dataset.poli === poliId) {
                        option.style.display = ""
                    } else {
                        option.style.display = "none"
                    }
                })
            }

            // Filter saat halaman load (jika ada old value)
            if (poliSelect.value) {
                filterJadwal(poliSelect.value)
            }

            poliSelect.addEventListener("change", function () {
                filterJadwal(this.value)
                jadwalSelect.value = ""
            })

            // Auto-dismiss toast
            setTimeout(() => {
                const toast = document.getElementById("toastSuccess")
                if (toast) toast.remove()
            }, 3000)
        })
    </script>
    @endpush
</x-layouts.app>
