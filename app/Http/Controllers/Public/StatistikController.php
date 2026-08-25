<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Peneliti;
use App\Models\Penelitian;
use App\Models\Buku;
use App\Models\Pkm;
use App\Models\Haki;
use App\Models\Publikasi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index(): Response
    {
        // 1. Tahun range
        $years = collect([
            Penelitian::pluck('tahun'),
            Buku::pluck('tahun'),
            Pkm::pluck('tahun'),
            Haki::pluck('tahun'),
            Publikasi::pluck('tahun'),
        ])->flatten()->unique()->sort()->values();

        if ($years->isEmpty()) {
            $years = collect([date('Y') - 1, date('Y')]);
        }

        // 2. Tren Output per Tahun
        $trendData = [
            'years' => $years,
            'penelitian' => $years->map(fn ($y) => Penelitian::where('tahun', $y)->count()),
            'buku' => $years->map(fn ($y) => Buku::where('tahun', $y)->count()),
            'pkm' => $years->map(fn ($y) => Pkm::where('tahun', $y)->count()),
            'haki' => $years->map(fn ($y) => Haki::where('tahun', $y)->count()),
            'publikasi' => $years->map(fn ($y) => Publikasi::where('tahun', $y)->count()),
        ];

        // 3. Distribusi Publikasi
        $pubDistribution = [
            'labels' => ['Scopus / Internasional', 'SINTA 1-2', 'SINTA 3-4', 'SINTA 5-6', 'Prosiding / Lainnya'],
            'series' => [
                Publikasi::where('kategori_indeks', 'like', '%Scopus%')->count(),
                Publikasi::where(function ($q) {
                    $q->where('kategori_indeks', 'like', '%SINTA 1%')
                      ->orWhere('kategori_indeks', 'like', '%SINTA 2%');
                })->count(),
                Publikasi::where(function ($q) {
                    $q->where('kategori_indeks', 'like', '%SINTA 3%')
                      ->orWhere('kategori_indeks', 'like', '%SINTA 4%');
                })->count(),
                Publikasi::where(function ($q) {
                    $q->where('kategori_indeks', 'like', '%SINTA 5%')
                      ->orWhere('kategori_indeks', 'like', '%SINTA 6%');
                })->count(),
                Publikasi::where('jenis_publikasi', 'prosiding')->count(),
            ],
        ];

        // 4. Top Peneliti Terproduktif
        $topPeneliti = Peneliti::with(['prodi.fakultas'])
            ->withCount(['penelitians', 'bukus', 'pkms', 'hakis', 'publikasis'])
            ->get()
            ->map(function ($p) {
                $total = $p->penelitians_count + $p->bukus_count + $p->pkms_count + $p->hakis_count + $p->publikasis_count;
                return [
                    'id' => $p->id,
                    'nama_lengkap' => $p->nama_lengkap,
                    'nidn' => $p->nidn,
                    'prodi' => $p->prodi?->nama ?? '-',
                    'fakultas' => $p->prodi?->fakultas?->nama ?? '-',
                    'total_karya' => $total,
                    'penelitian_count' => $p->penelitians_count,
                    'publikasi_count' => $p->publikasis_count,
                    'haki_count' => $p->hakis_count,
                ];
            })
            ->sortByDesc('total_karya')
            ->take(8)
            ->values();

        // 5. Total counts
        $counts = [
            'total_karya' => Penelitian::count() + Buku::count() + Pkm::count() + Haki::count() + Publikasi::count(),
            'total_peneliti' => Peneliti::where('status', 'aktif')->count(),
            'total_publikasi' => Publikasi::count(),
            'total_penelitian' => Penelitian::count(),
            'total_pkm' => Pkm::count(),
            'total_haki' => Haki::count(),
            'total_buku' => Buku::count(),
        ];

        return Inertia::render('Public/Statistik', [
            'trendData' => $trendData,
            'pubDistribution' => $pubDistribution,
            'topPeneliti' => $topPeneliti,
            'counts' => $counts,
        ]);
    }
}
