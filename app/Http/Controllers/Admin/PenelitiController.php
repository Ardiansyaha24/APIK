<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PenelitiController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');

        $query = Peneliti::withCount(['penelitians', 'bukus', 'pkms', 'hakis', 'publikasis']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('bidang_keahlian', 'like', "%{$search}%");
            });
        }

        $penelitis = $query->latest('id')->paginate(15)->withQueryString();

        return Inertia::render('Admin/Peneliti/Index', [
            'penelitis' => $penelitis,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->query('q', '');
        $results = Peneliti::where(function ($query) use ($q) {
                $query->where('nama_lengkap', 'like', "%{$q}%")
                      ->orWhere('nidn', 'like', "%{$q}%");
            })
            ->take(20)
            ->get(['id', 'nama_lengkap', 'nidn']);

        return response()->json($results);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nidn' => ['nullable', 'string', 'max:50', 'unique:penelitis,nidn'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
            'sinta_id' => ['nullable', 'string', 'max:50'],
            'scopus_id' => ['nullable', 'string', 'max:50'],
            'gscholar_id' => ['nullable', 'string', 'max:100'],
            'orcid_id' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:aktif,tidak_aktif,purna_tugas'],
        ]);

        $peneliti = Peneliti::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'peneliti' => $peneliti,
                'message' => 'Peneliti baru berhasil ditambahkan.',
            ]);
        }

        return back()->with('success', "Peneliti {$peneliti->nama_lengkap} berhasil didaftarkan.");
    }

    public function update(Request $request, Peneliti $peneliti): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nidn' => ['nullable', 'string', 'max:50', 'unique:penelitis,nidn,' . $peneliti->id],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
            'sinta_id' => ['nullable', 'string', 'max:50'],
            'scopus_id' => ['nullable', 'string', 'max:50'],
            'gscholar_id' => ['nullable', 'string', 'max:100'],
            'orcid_id' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:aktif,tidak_aktif,purna_tugas'],
        ]);

        $peneliti->update($validated);

        return back()->with('success', "Data peneliti {$peneliti->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(Peneliti $peneliti): RedirectResponse
    {
        $peneliti->delete();
        return back()->with('success', "Peneliti {$peneliti->nama_lengkap} berhasil dihapus.");
    }
}
