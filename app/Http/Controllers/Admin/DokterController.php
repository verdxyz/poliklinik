<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = User::query()
            ->where('role', 'dokter')
            ->with('poli')
            ->orderBy('nama')
            ->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::query()->orderBy('nama_poli')->get();

        return view('admin.dokter.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:16', 'unique:users,no_ktp'],
            'no_hp' => ['required', 'string', 'max:15'],
            'id_poli' => ['required', 'integer', 'exists:poli,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit(User $dokter)
    {
        abort_unless($dokter->role === 'dokter', 404);
        $polis = Poli::query()->orderBy('nama_poli')->get();

        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, User $dokter)
    {
        abort_unless($dokter->role === 'dokter', 404);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:16', 'unique:users,no_ktp,' . $dokter->id],
            'no_hp' => ['required', 'string', 'max:15'],
            'id_poli' => ['required', 'integer', 'exists:poli,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $dokter->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $changes = collect($validated)->except('password')->all();
        if (!empty($validated['password'])) {
            $changes['password'] = Hash::make($validated['password']);
        }

        $dokter->update($changes);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(User $dokter)
    {
        abort_unless($dokter->role === 'dokter', 404);
        $dokter->delete();

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}
