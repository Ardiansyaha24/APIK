<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    public const KATEGORI_JURNAL = [
        'Jurnal Internasional Bereputasi (Scopus Q1)',
        'Jurnal Internasional Bereputasi (Scopus Q2)',
        'Jurnal Internasional Bereputasi (Scopus Q3)',
        'Jurnal Internasional Bereputasi (Scopus Q4)',
        'Jurnal Internasional Terindeks (DOAJ/Copernicus/dll)',
        'Jurnal Nasional Terakreditasi (SINTA 1)',
        'Jurnal Nasional Terakreditasi (SINTA 2)',
        'Jurnal Nasional Terakreditasi (SINTA 3)',
        'Jurnal Nasional Terakreditasi (SINTA 4)',
        'Jurnal Nasional Terakreditasi (SINTA 5)',
        'Jurnal Nasional Terakreditasi (SINTA 6)',
        'Jurnal Nasional Tidak Terakreditasi',
    ];

    public const KATEGORI_PROSIDING = [
        'Prosiding Internasional Terindeks (Scopus/IEEE/AIP/dll)',
        'Prosiding Internasional Tidak Terindeks',
        'Prosiding Nasional Terindeks',
        'Prosiding Seminar Nasional',
    ];

    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $jenis = $request->query('jenis', '');
        $tahun = $request->query('tahun', '');

        $query = Publikasi::with(['penuliss']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nama_jurnal_prosiding', 'like', "%{$search}%")
                  ->orWhere('kategori_indeks', 'like', "%{$search}%")
                  ->orWhereHas('penuliss', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($jenis) {
            $query->where('jenis_publikasi', $jenis);
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $publikasis = $query->latest('tahun')->latest('id')->paginate(15)->withQueryString();
        $penelitis = Peneliti::where('status', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nidn']);

        $kategoriJurnal = \App\Models\KategoriPublikasi::whereIn('jenis', ['jurnal', 'keduanya'])->pluck('nama')->toArray();
        $kategoriProsiding = \App\Models\KategoriPublikasi::whereIn('jenis', ['prosiding', 'keduanya'])->pluck('nama')->toArray();

        return Inertia::render('Admin/Publikasi/Index', [
            'publikasis' => $publikasis,
            'penelitis' => $penelitis,
            'kategoriJurnal' => $kategoriJurnal,
            'kategoriProsiding' => $kategoriProsiding,
            'filters' => [
                'search' => $search,
                'jenis' => $jenis,
                'tahun' => $tahun,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_publikasi' => ['required', 'in:jurnal,prosiding'],
            'kategori_indeks' => ['required', 'string'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'nama_jurnal_prosiding' => ['required', 'string', 'max:255'],
            'tautan_doi' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'penulis_ids' => ['required', 'array', 'min:1'],
            'penulis_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('dokumen/publikasi', 'public');
        }

        $publikasi = Publikasi::create($validated);

        $syncData = [];
        foreach ($request->input('penulis_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Penulis Pertama' : ($index === 1 ? 'Penulis Korespondensi' : 'Anggota Penulis'),
            ];
        }
        $publikasi->penuliss()->sync($syncData);

        return back()->with('success', 'Data publikasi ilmiah berhasil disimpan.');
    }

    public function update(Request $request, Publikasi $publikasi): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_publikasi' => ['required', 'in:jurnal,prosiding'],
            'kategori_indeks' => ['required', 'string'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'nama_jurnal_prosiding' => ['required', 'string', 'max:255'],
            'tautan_doi' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'penulis_ids' => ['required', 'array', 'min:1'],
            'penulis_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            if ($publikasi->file_path && Storage::disk('public')->exists($publikasi->file_path)) {
                Storage::disk('public')->delete($publikasi->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('dokumen/publikasi', 'public');
        }

        $publikasi->update($validated);

        $syncData = [];
        foreach ($request->input('penulis_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Penulis Pertama' : ($index === 1 ? 'Penulis Korespondensi' : 'Anggota Penulis'),
            ];
        }
        $publikasi->penuliss()->sync($syncData);

        return back()->with('success', 'Data publikasi ilmiah berhasil diperbarui.');
    }

    public function destroy(Publikasi $publikasi): RedirectResponse
    {
        if ($publikasi->file_path && Storage::disk('public')->exists($publikasi->file_path)) {
            Storage::disk('public')->delete($publikasi->file_path);
        }
        $publikasi->delete();

        return back()->with('success', 'Data publikasi ilmiah berhasil dihapus.');
    }
}
