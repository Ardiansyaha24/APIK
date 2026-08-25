<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peneliti;
use App\Models\Penelitian;
use App\Models\Buku;
use App\Models\Pkm;
use App\Models\Haki;
use App\Models\Publikasi;
use App\Models\Prodi;
use App\Models\Fakultas;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $counts = [
            'peneliti' => Peneliti::count(),
            'penelitian' => Penelitian::count(),
            'buku' => Buku::count(),
            'pkm' => Pkm::count(),
            'haki' => Haki::count(),
            'publikasi' => Publikasi::count(),
            'prodi' => Prodi::count(),
            'fakultas' => Fakultas::count(),
        ];

        $latestOutputs = collect([
            Penelitian::with('penelitis')->latest()->take(3)->get()->map(fn ($p) => [
                'type' => 'Penelitian',
                'badge' => 'emerald',
                'title' => $p->judul,
                'year' => $p->tahun,
                'author' => $p->penelitis->pluck('nama_lengkap')->join(', '),
                'created_at' => $p->created_at->diffForHumans(),
            ]),
            Publikasi::with('penuliss')->latest()->take(3)->get()->map(fn ($p) => [
                'type' => 'Publikasi',
                'badge' => 'blue',
                'title' => $p->judul,
                'year' => $p->tahun,
                'author' => $p->penuliss->pluck('nama_lengkap')->join(', '),
                'created_at' => $p->created_at->diffForHumans(),
            ]),
            Haki::with('penciptas')->latest()->take(2)->get()->map(fn ($p) => [
                'type' => 'HKI',
                'badge' => 'cyan',
                'title' => $p->judul_ciptaan,
                'year' => $p->tahun,
                'author' => $p->penciptas->pluck('nama_lengkap')->join(', '),
                'created_at' => $p->created_at->diffForHumans(),
            ]),
        ])->flatten(1)->sortByDesc('created_at')->values()->take(6);

        return Inertia::render('Admin/Dashboard', [
            'counts' => $counts,
            'latestOutputs' => $latestOutputs,
        ]);
    }
}
