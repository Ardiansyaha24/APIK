<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\Buku;
use App\Models\Pkm;
use App\Models\Haki;
use App\Models\Publikasi;
use App\Models\Peneliti;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        // 1. Hitung Statistik Ringkasan
        $stats = [
            'total_penelitian' => Penelitian::count(),
            'total_publikasi' => Publikasi::count(),
            'total_pkm' => Pkm::count(),
            'total_buku' => Buku::count(),
            'total_haki' => Haki::count(),
            'total_karya' => Penelitian::count() + Publikasi::count() + Pkm::count() + Buku::count() + Haki::count(),
            'total_peneliti' => Peneliti::where('status', 'aktif')->count(),
        ];

        // 2. Ambil Beberapa Karya Terbaru
        $recentKarya = collect();

        $recentPub = Publikasi::with('penuliss')->latest('id')->take(3)->get()->map(fn ($item) => [
            'id' => $item->id,
            'type' => 'publikasi',
            'type_label' => ucfirst($item->jenis_publikasi),
            'badge_color' => 'blue',
            'title' => $item->judul,
            'year' => $item->tahun,
            'meta' => $item->nama_jurnal_prosiding . ($item->kategori_indeks ? " ({$item->kategori_indeks})" : ''),
            'authors' => $item->penuliss->pluck('nama_lengkap')->take(2)->join(', '),
        ]);
        $recentKarya = $recentKarya->concat($recentPub);

        $recentPen = Penelitian::with('penelitis', 'skemaBantuan')->latest('id')->take(3)->get()->map(fn ($item) => [
            'id' => $item->id,
            'type' => 'penelitian',
            'type_label' => 'Penelitian',
            'badge_color' => 'emerald',
            'title' => $item->judul,
            'year' => $item->tahun,
            'meta' => $item->skemaBantuan?->nama ?? 'Hibah Riset',
            'authors' => $item->penelitis->pluck('nama_lengkap')->take(2)->join(', '),
        ]);
        $recentKarya = $recentKarya->concat($recentPen);

        $recentBuku = Buku::with('penuliss')->latest('id')->take(2)->get()->map(fn ($item) => [
            'id' => $item->id,
            'type' => 'buku',
            'type_label' => 'Buku',
            'badge_color' => 'violet',
            'title' => $item->judul,
            'year' => $item->tahun,
            'meta' => $item->nama_penerbit,
            'authors' => $item->penuliss->pluck('nama_lengkap')->take(2)->join(', '),
        ]);
        $recentKarya = $recentKarya->concat($recentBuku);

        $recentHaki = Haki::with('penciptas')->latest('id')->take(2)->get()->map(fn ($item) => [
            'id' => $item->id,
            'type' => 'haki',
            'type_label' => 'HKI',
            'badge_color' => 'cyan',
            'title' => $item->judul_ciptaan,
            'year' => $item->tahun,
            'meta' => $item->jenis_ciptaan,
            'authors' => $item->penciptas->pluck('nama_lengkap')->take(2)->join(', '),
        ]);
        $recentKarya = $recentKarya->concat($recentHaki);

        // Ambil 6 karya terbaru secara acak/teratas
        $recentKarya = $recentKarya->take(6)->values();

        return Inertia::render('Public/Home', [
            'stats' => $stats,
            'recentKarya' => $recentKarya,
        ]);
    }
}
