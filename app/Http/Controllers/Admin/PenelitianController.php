<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\Peneliti;
use App\Models\SkemaBantuan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PenelitianController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $tahun = $request->query('tahun', '');

        $query = Penelitian::with(['penelitis', 'skemaBantuan']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor', 'like', "%{$search}%")
                  ->orWhereHas('penelitis', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $penelitians = $query->latest('tahun')->latest('id')->paginate(15)->withQueryString();
        $skemas = SkemaBantuan::whereIn('jenis', ['penelitian', 'keduanya'])->get();
        $penelitis = Peneliti::where('status', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nidn']);

        return Inertia::render('Admin/Penelitian/Index', [
            'penelitians' => $penelitians,
            'skemas' => $skemas,
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
            'skema_bantuan_id' => ['nullable'],
            'skema_bantuan_nama' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'tautan_tagihan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'peneliti_ids' => ['required', 'array', 'min:1'],
            'peneliti_ids.*' => ['exists:penelitis,id'],
        ]);

        if (!empty($validated['skema_bantuan_nama'])) {
            $skema = SkemaBantuan::firstOrCreate(
                ['nama' => trim($validated['skema_bantuan_nama'])],
                ['jenis' => 'penelitian']
            );
            $validated['skema_bantuan_id'] = $skema->id;
        }
        unset($validated['skema_bantuan_nama']);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumen/penelitian', 'public');
            $validated['file_path'] = $path;
        }

        $penelitian = Penelitian::create($validated);

        // Sync penelitis with order & roles
        $syncData = [];
        foreach ($request->input('peneliti_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Ketua Peneliti' : 'Anggota Peneliti',
            ];
        }
        $penelitian->penelitis()->sync($syncData);

        return back()->with('success', 'Data penelitian berhasil disimpan.');
    }

    public function update(Request $request, Penelitian $penelitian): RedirectResponse
    {
        $validated = $request->validate([
            'nomor' => ['nullable', 'string', 'max:100'],
            'skema_bantuan_id' => ['nullable'],
            'skema_bantuan_nama' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'tautan_tagihan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'peneliti_ids' => ['required', 'array', 'min:1'],
            'peneliti_ids.*' => ['exists:penelitis,id'],
        ]);

        if (!empty($validated['skema_bantuan_nama'])) {
            $skema = SkemaBantuan::firstOrCreate(
                ['nama' => trim($validated['skema_bantuan_nama'])],
                ['jenis' => 'penelitian']
            );
            $validated['skema_bantuan_id'] = $skema->id;
        }
        unset($validated['skema_bantuan_nama']);

        if ($request->hasFile('file')) {
            if ($penelitian->file_path && Storage::disk('public')->exists($penelitian->file_path)) {
                Storage::disk('public')->delete($penelitian->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('dokumen/penelitian', 'public');
        }

        $penelitian->update($validated);

        $syncData = [];
        foreach ($request->input('peneliti_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Ketua Peneliti' : 'Anggota Peneliti',
            ];
        }
        $penelitian->penelitis()->sync($syncData);

        return back()->with('success', 'Data penelitian berhasil diperbarui.');
    }

    public function destroy(Penelitian $penelitian): RedirectResponse
    {
        if ($penelitian->file_path && Storage::disk('public')->exists($penelitian->file_path)) {
            Storage::disk('public')->delete($penelitian->file_path);
        }
        $penelitian->delete();

        return back()->with('success', 'Data penelitian berhasil dihapus.');
    }
}
