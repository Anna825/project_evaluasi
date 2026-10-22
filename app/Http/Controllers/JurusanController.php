<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::withCount('prodi')
            ->orderBy('nama')
            ->paginate(10);

        return view('jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('jurusan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:jurusan,nama'],
            'kajur' => ['nullable', 'string', 'max:255'],
        ]);

        Jurusan::create($validated);

        return redirect()
            ->route('jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $jurusan = Jurusan::with('prodi')->findOrFail($id);

        return view('jurusan.show', compact('jurusan'));
    }

    public function edit(string $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        return view('jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, string $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jurusan', 'nama')->ignore($jurusan->id),
            ],
            'kajur' => ['nullable', 'string', 'max:255'],
        ]);

        $jurusan->update($validated);

        return redirect()
            ->route('jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        if ($jurusan->prodi()->exists()) {
            return redirect()
                ->route('jurusan.index')
                ->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki program studi.');
        }

        $jurusan->delete();

        return redirect()
            ->route('jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
