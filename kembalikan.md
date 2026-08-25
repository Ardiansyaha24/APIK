# Panduan Pemulihan Fitur Penuh APIK (kembalikan.md)

Dokumen ini mencatat seluruh komponen, menu, dan fungsionalitas yang disembunyikan/dikunci sementara selama **Fase 1 (Landing Page Showcase)**, beserta panduan langkah demi langkah untuk mengembalikannya ke kondisi penuh (*Full Active Mode*) saat siap diluncurkan ke klien.

---

## Ringkasan Fitur yang Dikunci Sementara

| Komponen / Berkas | Kondisi Saat Ini (Showcase) | Kondisi Saat Dipulihkan (Full Active) |
|---|---|---|
| **Navbar Utama & Mobile** (`PublicLayout.vue`) | Hanya menampilkan *Beranda*, *5 Pilar*, *Keunggulan* | Menampilkan *Beranda*, *Direktori Karya* (`/direktori`), *Daftar Peneliti* (`/peneliti`), *Statistik* (`/statistik`) |
| **Tombol Masuk Admin** (`PublicLayout.vue`) | Menampilkan Modal Popup *"Dalam Pengembangan"* | Tautan langsung menuju halaman login (`<Link href="/login">`) |
| **Pencarian Hero** (`Home.vue`) | Menampilkan Modal Popup info | Mengarahkan query langsung ke halaman direktori (`/direktori?search=...`) |
| **Kartu 5 Pilar Luaran** (`Home.vue`) | Kartu statis informatif | Kartu tautan interaktif dengan filter langsung (`/direktori?type=penelitian`, dll) |
| **Halaman Login Admin** (`Login.vue`) | Input & tombol disabled dengan banner penguncian | Form aktif normal untuk autentikasi admin (`admin@apik.local`) |

---

## Langkah Detail Pemulihan (*How to Restore*)

### 1. Buka Kembali Menu Navigasi di `resources/js/Layouts/PublicLayout.vue`
Ganti bagian `<nav>` desktop & mobile menu dengan rute lengkap:
```vue
<!-- Desktop Menu -->
<nav class="hidden md:flex items-center gap-1 text-xs font-medium">
    <Link href="/" :class="['px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5', $page.url === '/' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800']">
        <Home class="w-3.5 h-3.5" /> Beranda
    </Link>
    <Link href="/direktori" :class="['px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5', $page.url.startsWith('/direktori') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800']">
        <Compass class="w-3.5 h-3.5" /> Direktori Karya
    </Link>
    <Link href="/peneliti" :class="['px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5', $page.url.startsWith('/peneliti') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800']">
        <Users class="w-3.5 h-3.5" /> Daftar Peneliti
    </Link>
    <Link href="/statistik" :class="['px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5', $page.url.startsWith('/statistik') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800']">
        <BarChart3 class="w-3.5 h-3.5" /> Statistik
    </Link>
</nav>

<!-- Tombol Masuk Admin -->
<Link href="/login" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-medium transition-colors">
    <Lock class="w-3.5 h-3.5 text-slate-400" />
    <span>Masuk Admin</span>
</Link>
```

---

### 2. Hubungkan Kembali Fitur Pencarian & Kartu di `resources/js/Pages/Public/Home.vue`
1. Pada fungsi `submitSearch`:
```javascript
const submitSearch = () => {
    router.get('/direktori', { search: quickSearch.value || undefined });
};
```
2. Ubah kartu 5 pilar dari `<div>` menjadi `<Link :href="'/direktori?type=' + tipe">`.
3. Aktifkan tombol CTA di hero menuju `/direktori` dan `/peneliti`.

---

### 3. Aktifkan Kembali Form di `resources/js/Pages/Auth/Login.vue`
Hapus atribut `disabled` pada input email, password, dan tombol submit, serta kembalikan fungsi `form.post('/login')`.

---

### 4. Eksekusi Build & Sinkronisasi Server
Setelah file di atas diperbarui, jalankan:
```bash
npm run build
git add .
git commit -m "pemulihan fitur penuh direktori karya dan portal admin"
git push origin main
```
Di terminal server `/www/wwwroot/apik.iain-manado.ac.id`:
```bash
git pull origin main
npm run build
php artisan view:clear && php artisan config:clear
```
Sistem akan kembali berjalan 100% dengan seluruh portal pencarian dan panel admin aktif.
