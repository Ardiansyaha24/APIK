<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PenelitiController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');

        $query = Peneliti::withCount(['penelitians', 'bukus', 'pkms', 'hakis', 'publikasis'])
            ->where('status', 'aktif');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%")
                  ->orWhere('bidang_keahlian', 'like', "%{$search}%");
            });
        }

        $penelitis = $query->orderBy('nama_lengkap')->get();

        return Inertia::render('Public/Peneliti/Index', [
            'penelitis' => $penelitis,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(Peneliti $peneliti): Response
    {
        $peneliti->load([
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
