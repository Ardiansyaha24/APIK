<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\DirectoryController;
use App\Http\Controllers\Public\PenelitiController as PublicPenelitiController;
use App\Http\Controllers\Public\StatistikController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PenelitiController as AdminPenelitiController;
use App\Http\Controllers\Admin\PenelitianController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PkmController;
use App\Http\Controllers\Admin\HakiController;
use App\Http\Controllers\Admin\PublikasiController;
use App\Http\Controllers\Admin\MasterDataController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/direktori', [DirectoryController::class, 'index'])->name('public.direktori');
Route::get('/peneliti', [PublicPenelitiController::class, 'index'])->name('public.peneliti.index');
Route::get('/peneliti/{peneliti}', [PublicPenelitiController::class, 'show'])->name('public.peneliti.show');
Route::get('/statistik', [StatistikController::class, 'index'])->name('public.statistik');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Management Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Peneliti & API Autocomplete
    Route::get('/peneliti/search', [AdminPenelitiController::class, 'search'])->name('peneliti.search');
    Route::resource('peneliti', AdminPenelitiController::class)->except(['create', 'edit', 'show']);

    // 5 Modul Output
    Route::resource('penelitian', PenelitianController::class)->except(['create', 'edit', 'show']);
    Route::resource('buku', BukuController::class)->except(['create', 'edit', 'show']);
    Route::resource('pkm', PkmController::class)->except(['create', 'edit', 'show']);
    Route::resource('haki', HakiController::class)->except(['create', 'edit', 'show']);
    Route::resource('publikasi', PublikasiController::class)->except(['create', 'edit', 'show']);

    // Master Data
    Route::get('/master', [MasterDataController::class, 'index'])->name('master.index');
    Route::post('/master/fakultas', [MasterDataController::class, 'storeFakultas'])->name('master.fakultas.store');
    Route::put('/master/fakultas/{fakultas}', [MasterDataController::class, 'updateFakultas'])->name('master.fakultas.update');
    Route::delete('/master/fakultas/{fakultas}', [MasterDataController::class, 'destroyFakultas'])->name('master.fakultas.destroy');

    Route::post('/master/prodi', [MasterDataController::class, 'storeProdi'])->name('master.prodi.store');
    Route::put('/master/prodi/{prodi}', [MasterDataController::class, 'updateProdi'])->name('master.prodi.update');
    Route::delete('/master/prodi/{prodi}', [MasterDataController::class, 'destroyProdi'])->name('master.prodi.destroy');

    Route::post('/master/skema', [MasterDataController::class, 'storeSkema'])->name('master.skema.store');
    Route::put('/master/skema/{skema}', [MasterDataController::class, 'updateSkema'])->name('master.skema.update');
    Route::delete('/master/skema/{skema}', [MasterDataController::class, 'destroySkema'])->name('master.skema.destroy');

    Route::post('/master/kategori-publikasi', [MasterDataController::class, 'storeKategoriPublikasi'])->name('master.kategori_publikasi.store');
    Route::put('/master/kategori-publikasi/{kategoriPublikasi}', [MasterDataController::class, 'updateKategoriPublikasi'])->name('master.kategori_publikasi.update');
    Route::delete('/master/kategori-publikasi/{kategoriPublikasi}', [MasterDataController::class, 'destroyKategoriPublikasi'])->name('master.kategori_publikasi.destroy');

    Route::post('/master/jenis-ciptaan', [MasterDataController::class, 'storeJenisCiptaan'])->name('master.jenis_ciptaan.store');
    Route::put('/master/jenis-ciptaan/{jenisCiptaan}', [MasterDataController::class, 'updateJenisCiptaan'])->name('master.jenis_ciptaan.update');
    Route::delete('/master/jenis-ciptaan/{jenisCiptaan}', [MasterDataController::class, 'destroyJenisCiptaan'])->name('master.jenis_ciptaan.destroy');
});
