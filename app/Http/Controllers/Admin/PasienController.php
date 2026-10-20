<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::query()
            ->where('role', 'pasien')
            ->orderBy('nama')
            ->get();

        return view('admin.pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:16', 'unique:users,no_ktp'],
            'no_hp' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $month = now()->format('Ym');
        $lastSequence = User::query()
            ->where('role', 'pasien')
            ->where('no_rm', 'like', "{$month}-%")
            ->pluck('no_rm')
            ->map(fn (string $noRm) => (int) substr($noRm, 7))
            ->max() ?? 0;

        User::create([
            ...$validated,
            'no_rm' => $month . '-' . str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT),
            'password' => Hash::make($validated['password']),
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function edit(User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);

        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:16', 'unique:users,no_ktp,' . $pasien->id],
            'no_hp' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $pasien->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $changes = collect($validated)->except('password')->all();
        if (!empty($validated['password'])) {
            $changes['password'] = Hash::make($validated['password']);
        }

        $pasien->update($changes);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
