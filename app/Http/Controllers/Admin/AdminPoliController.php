<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;

class AdminPoliController extends Controller
{
    public function index()
    {
        $polis = Poli::query()->orderBy('nama_poli')->get();

        return view('admin.polis.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.polis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:25', 'unique:poli,nama_poli'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Poli::create($validated);

        return redirect()->route('polis.index')->with('success', 'Poli berhasil ditambahkan.');
    }

    public function edit(Poli $poli)
    {
        return view('admin.polis.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:25', 'unique:poli,nama_poli,' . $poli->id],
            'keterangan' => ['nullable', 'string'],
        ]);

        $poli->update($validated);

        return redirect()->route('polis.index')->with('success', 'Poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();

        return redirect()->route('polis.index')->with('success', 'Poli berhasil dihapus.');
    }
}
