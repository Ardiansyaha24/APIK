<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pkm;
use App\Models\Peneliti;
use App\Models\SkemaBantuan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PkmController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $tahun = $request->query('tahun', '');

        $query = Pkm::with(['pengabdis', 'skemaBantuan']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor', 'like', "%{$search}%")
                  ->orWhereHas('pengabdis', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $pkms = $query->latest('tahun')->latest('id')->paginate(15)->withQueryString();
        $skemas = SkemaBantuan::whereIn('jenis', ['pkm', 'keduanya'])->get();
        $penelitis = Peneliti::where('status', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nidn']);

        return Inertia::render('Admin/Pkm/Index', [
            'pkms' => $pkms,
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
            'pengabdi_ids' => ['required', 'array', 'min:1'],
            'pengabdi_ids.*' => ['exists:penelitis,id'],
        ]);

        if (!empty($validated['skema_bantuan_nama'])) {
            $skema = SkemaBantuan::firstOrCreate(
                ['nama' => trim($validated['skema_bantuan_nama'])],
                ['jenis' => 'pkm']
            );
            $validated['skema_bantuan_id'] = $skema->id;
        }
        unset($validated['skema_bantuan_nama']);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('dokumen/pkm', 'public');
        }

        $pkm = Pkm::create($validated);

        $syncData = [];
        foreach ($request->input('pengabdi_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Ketua Pengabdi' : 'Anggota Pengabdi',
            ];
        }
        $pkm->pengabdis()->sync($syncData);

        return back()->with('success', 'Data PKM berhasil disimpan.');
    }

    public function update(Request $request, Pkm $pkm): RedirectResponse
    {
        $validated = $request->validate([
            'nomor' => ['nullable', 'string', 'max:100'],
            'skema_bantuan_id' => ['nullable'],
            'skema_bantuan_nama' => ['nullable', 'string', 'max:255'],
            'judul' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'tautan_tagihan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'pengabdi_ids' => ['required', 'array', 'min:1'],
            'pengabdi_ids.*' => ['exists:penelitis,id'],
        ]);

        if (!empty($validated['skema_bantuan_nama'])) {
            $skema = SkemaBantuan::firstOrCreate(
                ['nama' => trim($validated['skema_bantuan_nama'])],
                ['jenis' => 'pkm']
            );
            $validated['skema_bantuan_id'] = $skema->id;
        }
        unset($validated['skema_bantuan_nama']);

        if ($request->hasFile('file')) {
            if ($pkm->file_path && Storage::disk('public')->exists($pkm->file_path)) {
                Storage::disk('public')->delete($pkm->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('dokumen/pkm', 'public');
        }

        $pkm->update($validated);

        $syncData = [];
        foreach ($request->input('pengabdi_ids') as $index => $id) {
            $syncData[$id] = [
                'urutan' => $index + 1,
                'peran' => $index === 0 ? 'Ketua Pengabdi' : 'Anggota Pengabdi',
            ];
        }
        $pkm->pengabdis()->sync($syncData);

        return back()->with('success', 'Data PKM berhasil diperbarui.');
    }

    public function destroy(Pkm $pkm): RedirectResponse
    {
        if ($pkm->file_path && Storage::disk('public')->exists($pkm->file_path)) {
            Storage::disk('public')->delete($pkm->file_path);
        }
        $pkm->delete();

        return back()->with('success', 'Data PKM berhasil dihapus.');
    }
}
