<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $tahun = $request->query('tahun', '');

        $query = Buku::with(['penuliss']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('nama_penerbit', 'like', "%{$search}%")
                  ->orWhereHas('penuliss', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $bukus = $query->latest('tahun')->latest('id')->paginate(15)->withQueryString();
        $penelitis = Peneliti::where('status', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nidn']);

        return Inertia::render('Admin/Buku/Index', [
            'bukus' => $bukus,
            'penelitis' => $penelitis,
            'filters' => [
                'search' => $search,
                'tahun' => $tahun,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor' => ['nullable', 'string', 'max:100'],
            'nama_penerbit' => ['required', 'string', 'max:255'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'tautan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'penulis_ids' => ['required', 'array', 'min:1'],
            'penulis_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('dokumen/buku', 'public');
        }

        $buku = Buku::create($validated);

        $syncData = [];
        foreach ($request->input('penulis_ids') as $index => $id) {
            $syncData[$id] = ['urutan' => $index + 1];
        }
        $buku->penuliss()->sync($syncData);

        return back()->with('success', 'Data buku berhasil disimpan.');
    }

    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $validated = $request->validate([
            'nomor' => ['nullable', 'string', 'max:100'],
            'nama_penerbit' => ['required', 'string', 'max:255'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'tautan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'penulis_ids' => ['required', 'array', 'min:1'],
            'penulis_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            if ($buku->file_path && Storage::disk('public')->exists($buku->file_path)) {
                Storage::disk('public')->delete($buku->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('dokumen/buku', 'public');
        }

        $buku->update($validated);

        $syncData = [];
        foreach ($request->input('penulis_ids') as $index => $id) {
            $syncData[$id] = ['urutan' => $index + 1];
        }
        $buku->penuliss()->sync($syncData);

        return back()->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        if ($buku->file_path && Storage::disk('public')->exists($buku->file_path)) {
            Storage::disk('public')->delete($buku->file_path);
        }
        $buku->delete();

        return back()->with('success', 'Data buku berhasil dihapus.');
    }
}
