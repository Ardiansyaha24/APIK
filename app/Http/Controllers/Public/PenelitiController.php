<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Peneliti;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PenelitiController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $prodiId = $request->query('prodi', '');
        $fakultasId = $request->query('fakultas', '');

        $query = Peneliti::with(['prodi.fakultas'])
            ->withCount(['penelitians', 'bukus', 'pkms', 'hakis', 'publikasis'])
            ->where('status', 'aktif');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%")
                  ->orWhere('bidang_keahlian', 'like', "%{$search}%");
            });
        }

        if ($prodiId) {
            $query->where('prodi_id', $prodiId);
        }

        if ($fakultasId) {
            $query->whereHas('prodi', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }

        $penelitis = $query->orderBy('nama_lengkap')->get();
        $prodis = Prodi::with('fakultas')->orderBy('nama')->get();
        $fakultasList = Fakultas::orderBy('nama')->get();

        return Inertia::render('Public/Peneliti/Index', [
            'penelitis' => $penelitis,
            'prodis' => $prodis,
            'fakultasList' => $fakultasList,
            'filters' => [
                'search' => $search,
                'prodi' => $prodiId,
                'fakultas' => $fakultasId,
            ],
        ]);
    }

    public function show(Peneliti $peneliti): Response
    {
        $peneliti->load([
            'prodi.fakultas',
            'penelitians.skemaBantuan',
            'bukus',
            'pkms.skemaBantuan',
            'hakis',
            'publikasis',
        ]);

        $totalKarya = $peneliti->penelitians->count()
            + $peneliti->bukus->count()
            + $peneliti->pkms->count()
            + $peneliti->hakis->count()
            + $peneliti->publikasis->count();

        return Inertia::render('Public/Peneliti/Show', [
            'peneliti' => $peneliti,
            'total_karya' => $totalKarya,
        ]);
    }
}
