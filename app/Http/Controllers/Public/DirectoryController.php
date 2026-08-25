<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\Buku;
use App\Models\Pkm;
use App\Models\Haki;
use App\Models\Publikasi;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DirectoryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');
        $type = $request->query('type', 'semua');
        $year = $request->query('year', '');
        $indexCat = $request->query('index_cat', '');

        $items = collect();

        // 1. Penelitian
        if (in_array($type, ['semua', 'penelitian'])) {
            $query = Penelitian::with(['penelitis', 'skemaBantuan']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhereHas('penelitis', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
                });
            }
            if ($year) {
                $query->where('tahun', $year);
            }
            $penelitians = $query->latest('tahun')->latest('id')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'penelitian',
                    'type_label' => 'Penelitian',
                    'badge_color' => 'emerald',
                    'title' => $item->judul,
                    'year' => $item->tahun,
                    'nomor' => $item->nomor,
                    'meta_info' => $item->skemaBantuan?->nama ?? 'Hibah Riset Internal',
                    'link_url' => $item->tautan_tagihan,
                    'file_path' => $item->file_path,
                    'contributors' => $item->penelitis->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->nama_lengkap,
                        'role' => $p->pivot->peran ?? 'Peneliti',
                    ]),
                ];
            });
            $items = $items->concat($penelitians);
        }

        // 2. Buku
        if (in_array($type, ['semua', 'buku'])) {
            $query = Buku::with(['penuliss']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%")
                      ->orWhere('nama_penerbit', 'like', "%{$search}%")
                      ->orWhereHas('penuliss', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
                });
            }
            if ($year) {
                $query->where('tahun', $year);
            }
            $bukus = $query->latest('tahun')->latest('id')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'buku',
                    'type_label' => 'Buku',
                    'badge_color' => 'violet',
                    'title' => $item->judul,
                    'year' => $item->tahun,
                    'nomor' => $item->isbn ? "ISBN: {$item->isbn}" : $item->nomor,
                    'meta_info' => $item->nama_penerbit ?? 'Penerbit Mandiri',
                    'link_url' => $item->tautan,
                    'file_path' => $item->file_path,
                    'contributors' => $item->penuliss->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->nama_lengkap,
                        'role' => 'Penulis',
                    ]),
                ];
            });
            $items = $items->concat($bukus);
        }

        // 3. PKM
        if (in_array($type, ['semua', 'pkm'])) {
            $query = Pkm::with(['pengabdis', 'skemaBantuan']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhereHas('pengabdis', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
                });
            }
            if ($year) {
                $query->where('tahun', $year);
            }
            $pkms = $query->latest('tahun')->latest('id')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'pkm',
                    'type_label' => 'Pengabdian (PKM)',
                    'badge_color' => 'amber',
                    'title' => $item->judul,
                    'year' => $item->tahun,
                    'nomor' => $item->nomor,
                    'meta_info' => $item->skemaBantuan?->nama ?? 'Program PKM',
                    'link_url' => $item->tautan_tagihan,
                    'file_path' => $item->file_path,
                    'contributors' => $item->pengabdis->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->nama_lengkap,
                        'role' => $p->pivot->peran ?? 'Pengabdi',
                    ]),
                ];
            });
            $items = $items->concat($pkms);
        }

        // 4. HKI / Paten
        if (in_array($type, ['semua', 'haki'])) {
            $query = Haki::with(['penciptas']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_ciptaan', 'like', "%{$search}%")
                      ->orWhere('nomor_hki', 'like', "%{$search}%")
                      ->orWhere('jenis_ciptaan', 'like', "%{$search}%")
                      ->orWhereHas('penciptas', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
                });
            }
            if ($year) {
                $query->where('tahun', $year);
            }
            $hakis = $query->latest('tahun')->latest('id')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'haki',
                    'type_label' => 'HKI / Hak Cipta',
                    'badge_color' => 'cyan',
                    'title' => $item->judul_ciptaan,
                    'year' => $item->tahun,
                    'nomor' => $item->nomor_hki ? "No. {$item->nomor_hki}" : 'Pencatatan HKI',
                    'meta_info' => "{$item->jenis_ciptaan} • Pemegang: " . ($item->pemegang_hak_cipta ?: 'Institusi'),
                    'link_url' => $item->tautan_tagihan,
                    'file_path' => $item->file_path,
                    'contributors' => $item->penciptas->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->nama_lengkap,
                        'role' => 'Pencipta',
                    ]),
                ];
            });
            $items = $items->concat($hakis);
        }

        // 5. Publikasi
        if (in_array($type, ['semua', 'publikasi', 'jurnal', 'prosiding'])) {
            $query = Publikasi::with(['penuliss']);
            if ($type === 'jurnal') {
                $query->where('jenis_publikasi', 'jurnal');
            } elseif ($type === 'prosiding') {
                $query->where('jenis_publikasi', 'prosiding');
            }
            if ($indexCat) {
                $query->where('kategori_indeks', 'like', "%{$indexCat}%");
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('nama_jurnal_prosiding', 'like', "%{$search}%")
                      ->orWhere('kategori_indeks', 'like', "%{$search}%")
                      ->orWhereHas('penuliss', fn ($p) => $p->where('nama_lengkap', 'like', "%{$search}%"));
                });
            }
            if ($year) {
                $query->where('tahun', $year);
            }
            $pubs = $query->latest('tahun')->latest('id')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'publikasi',
                    'type_label' => ucfirst($item->jenis_publikasi),
                    'badge_color' => 'blue',
                    'title' => $item->judul,
                    'year' => $item->tahun,
                    'nomor' => $item->kategori_indeks,
                    'meta_info' => $item->nama_jurnal_prosiding,
                    'link_url' => $item->tautan_doi,
                    'file_path' => $item->file_path,
                    'contributors' => $item->penuliss->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->nama_lengkap,
                        'role' => $p->pivot->peran ?? 'Penulis',
                    ]),
                ];
            });
            $items = $items->concat($pubs);
        }

        // Sort items by year desc, id desc
        $sortedItems = $items->sortByDesc('year')->values();

        // Hitung total ringkasan
        $stats = [
            'total_karya' => $sortedItems->count(),
            'total_penelitian' => Penelitian::count(),
            'total_buku' => Buku::count(),
            'total_pkm' => Pkm::count(),
            'total_haki' => Haki::count(),
            'total_publikasi' => Publikasi::count(),
            'total_peneliti' => Peneliti::where('status', 'aktif')->count(),
        ];

        // Available years
        $years = collect([
            Penelitian::pluck('tahun'),
            Buku::pluck('tahun'),
            Pkm::pluck('tahun'),
            Haki::pluck('tahun'),
            Publikasi::pluck('tahun'),
        ])->flatten()->unique()->sortDesc()->values();

        return Inertia::render('Public/Directory', [
            'items' => $sortedItems,
            'stats' => $stats,
            'years' => $years,
            'filters' => [
                'search' => $search,
                'type' => $type,
                'year' => $year,
                'index_cat' => $indexCat,
            ],
        ]);
    }
}
