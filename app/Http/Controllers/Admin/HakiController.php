<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Haki;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class HakiController extends Controller
{
    public const JENIS_CIPTAAN = [
        'Buku, pamflet, dan semua hasil karya tulis lainnya',
        'Ceramah, kuliah, pidato, dan ciptaan sejenis',
        'Alat peraga untuk pendidikan dan ilmu pengetahuan',
        'Lagu dan/atau musik dengan atau tanpa teks',
        'Drama, drama musikal, tari, koreografi, pewayangan, pantomim',
        'Karya seni rupa (lukisan, gambar, ukiran, kaligrafi, patung)',
        'Karya seni terapan',
        'Karya arsitektur',
        'Peta',
        'Karya seni batik atau seni motif lain',
        'Karya fotografi',
        'Potret',
        'Karya sinematografi',
        'Terjemahan, tafsir, saduran, bunga rampai, basis data',
        'Kompilasi ciptaan atau data',
        'Permainan video',
        'Program komputer / Aplikasi Perangkat Lunak',
    ];

    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $tahun = $request->query('tahun', '');

        $query = Haki::with(['penciptas']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_ciptaan', 'like', "%{$search}%")
                  ->orWhere('nomor_hki', 'like', "%{$search}%")
                  ->orWhere('jenis_ciptaan', 'like', "%{$search}%")
                  ->orWhereHas('penciptas', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $hakis = $query->latest('tahun')->latest('id')->paginate(15)->withQueryString();
        $penelitis = Peneliti::where('status', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nidn']);

        return Inertia::render('Admin/Haki/Index', [
            'hakis' => $hakis,
            'penelitis' => $penelitis,
            'jenisCiptaanList' => self::JENIS_CIPTAAN,
            'filters' => [
                'search' => $search,
                'tahun' => $tahun,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_hki' => ['nullable', 'string', 'max:100'],
            'pemegang_hak_cipta' => ['nullable', 'string', 'max:255'],
            'jenis_ciptaan' => ['required', 'string'],
            'judul_ciptaan' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'tautan_tagihan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'pencipta_ids' => ['required', 'array', 'min:1'],
            'pencipta_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('dokumen/haki', 'public');
        }

        $haki = Haki::create($validated);

        $syncData = [];
        foreach ($request->input('pencipta_ids') as $index => $id) {
            $syncData[$id] = ['urutan' => $index + 1];
        }
        $haki->penciptas()->sync($syncData);

        return back()->with('success', 'Data HKI / Hak Cipta berhasil disimpan.');
    }

    public function update(Request $request, Haki $haki): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_hki' => ['nullable', 'string', 'max:100'],
            'pemegang_hak_cipta' => ['nullable', 'string', 'max:255'],
            'jenis_ciptaan' => ['required', 'string'],
            'judul_ciptaan' => ['required', 'string'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'tautan_tagihan' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'pencipta_ids' => ['required', 'array', 'min:1'],
            'pencipta_ids.*' => ['exists:penelitis,id'],
        ]);

        if ($request->hasFile('file')) {
            if ($haki->file_path && Storage::disk('public')->exists($haki->file_path)) {
                Storage::disk('public')->delete($haki->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('dokumen/haki', 'public');
        }

        $haki->update($validated);

        $syncData = [];
        foreach ($request->input('pencipta_ids') as $index => $id) {
            $syncData[$id] = ['urutan' => $index + 1];
        }
        $haki->penciptas()->sync($syncData);

        return back()->with('success', 'Data HKI / Hak Cipta berhasil diperbarui.');
    }

    public function destroy(Haki $haki): RedirectResponse
    {
        if ($haki->file_path && Storage::disk('public')->exists($haki->file_path)) {
            Storage::disk('public')->delete($haki->file_path);
        }
        $haki->delete();

        return back()->with('success', 'Data HKI / Hak Cipta berhasil dihapus.');
    }
}
