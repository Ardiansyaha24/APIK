<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\SkemaBantuan;
use App\Models\KategoriPublikasi;
use App\Models\JenisCiptaan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class MasterDataController extends Controller
{
    public function index(): Response
    {
        $fakultasList = Fakultas::with('prodis')->orderBy('nama')->get();
        $skemaList = SkemaBantuan::orderBy('nama')->get();
        $kategoriPublikasiList = KategoriPublikasi::orderBy('nama')->get();
        $jenisCiptaanList = JenisCiptaan::orderBy('nama')->get();

        return Inertia::render('Admin/Master/Index', [
            'fakultasList' => $fakultasList,
            'skemaList' => $skemaList,
            'kategoriPublikasiList' => $kategoriPublikasiList,
            'jenisCiptaanList' => $jenisCiptaanList,
        ]);
    }

    // Fakultas
    public function storeFakultas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['nullable', 'string', 'max:20'],
        ]);
        Fakultas::create($validated);
        return back()->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function updateFakultas(Request $request, Fakultas $fakultas): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['nullable', 'string', 'max:20'],
        ]);
        $fakultas->update($validated);
        return back()->with('success', 'Data fakultas berhasil diperbarui.');
    }

    public function destroyFakultas(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->delete();
        return back()->with('success', 'Fakultas berhasil dihapus.');
    }

    // Prodi
    public function storeProdi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'string', 'max:10'],
            'kode' => ['nullable', 'string', 'max:20'],
        ]);
        Prodi::create($validated);
        return back()->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function updateProdi(Request $request, Prodi $prodi): RedirectResponse
    {
        $validated = $request->validate([
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'string', 'max:10'],
            'kode' => ['nullable', 'string', 'max:20'],
        ]);
        $prodi->update($validated);
        return back()->with('success', 'Data program studi berhasil diperbarui.');
    }

    public function destroyProdi(Prodi $prodi): RedirectResponse
    {
        $prodi->delete();
        return back()->with('success', 'Program studi berhasil dihapus.');
    }

    // Skema Bantuan
    public function storeSkema(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:penelitian,pkm,keduanya'],
            'keterangan' => ['nullable', 'string'],
        ]);
        SkemaBantuan::create($validated);
        return back()->with('success', 'Skema bantuan berhasil ditambahkan.');
    }

    public function updateSkema(Request $request, SkemaBantuan $skema): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:penelitian,pkm,keduanya'],
            'keterangan' => ['nullable', 'string'],
        ]);
        $skema->update($validated);
        return back()->with('success', 'Skema bantuan berhasil diperbarui.');
    }

    public function destroySkema(SkemaBantuan $skema): RedirectResponse
    {
        $skema->delete();
        return back()->with('success', 'Skema bantuan berhasil dihapus.');
    }

    // Kategori Publikasi
    public function storeKategoriPublikasi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:jurnal,prosiding,keduanya'],
            'keterangan' => ['nullable', 'string'],
        ]);
        KategoriPublikasi::create($validated);
        return back()->with('success', 'Kategori publikasi berhasil ditambahkan.');
    }

    public function updateKategoriPublikasi(Request $request, KategoriPublikasi $kategoriPublikasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:jurnal,prosiding,keduanya'],
            'keterangan' => ['nullable', 'string'],
        ]);
        $kategoriPublikasi->update($validated);
        return back()->with('success', 'Kategori publikasi berhasil diperbarui.');
    }

    public function destroyKategoriPublikasi(KategoriPublikasi $kategoriPublikasi): RedirectResponse
    {
        $kategoriPublikasi->delete();
        return back()->with('success', 'Kategori publikasi berhasil dihapus.');
    }

    // Jenis Ciptaan HKI
    public function storeJenisCiptaan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);
        JenisCiptaan::create($validated);
        return back()->with('success', 'Jenis ciptaan berhasil ditambahkan.');
    }

    public function updateJenisCiptaan(Request $request, JenisCiptaan $jenisCiptaan): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);
        $jenisCiptaan->update($validated);
        return back()->with('success', 'Jenis ciptaan berhasil diperbarui.');
    }

    public function destroyJenisCiptaan(JenisCiptaan $jenisCiptaan): RedirectResponse
    {
        $jenisCiptaan->delete();
        return back()->with('success', 'Jenis ciptaan berhasil dihapus.');
    }
}
