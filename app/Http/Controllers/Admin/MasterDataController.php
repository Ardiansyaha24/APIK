<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\SkemaBantuan;
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

        return Inertia::render('Admin/Master/Index', [
            'fakultasList' => $fakultasList,
            'skemaList' => $skemaList,
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
}
