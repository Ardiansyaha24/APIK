<template>
    <PublicLayout>
        <Head title="APIK — Akselerasi Penelitian, Inovasi, dan Kolaborasi" />

        <!-- 1. Hero Section (Deep Navy Elegant) -->
        <section class="relative bg-[#0F172A] text-white pt-14 pb-18 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-slate-800">
            <!-- Ambient Glow -->
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-5xl mx-auto text-center relative z-10 space-y-6">
                <!-- Tagline Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-semibold tracking-wide">
                    <Sparkles class="w-3.5 h-3.5" />
                    Pangkalan Data Riset, Inovasi & Publikasi Ilmiah
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight sm:leading-snug">
                    Akselerasi Penelitian, Inovasi, <br class="hidden sm:inline" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-indigo-300">
                        dan Kolaborasi Akademik
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
                    Sistem informasi satu pintu untuk mendokumentasikan, menelusuri, dan mempublikasikan seluruh rekam jejak riset, buku ajar, pengabdian masyarakat, paten, dan publikasi bereputasi.
                </p>

                <!-- Hero Search Bar -->
                <div class="pt-2 max-w-2xl mx-auto">
                    <form @submit.prevent="openNotice" class="relative flex items-center shadow-lg rounded-2xl">
                        <Search class="w-4 h-4 text-blue-400 absolute left-4 pointer-events-none" />
                        <input 
                            type="text" 
                            v-model="quickSearch"
                            placeholder="Cari judul artikel, nama dosen/peneliti, nomor SK, atau ISBN..."
                            class="w-full pl-11 pr-28 py-3 bg-slate-900/90 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <button 
                            type="submit" 
                            class="absolute right-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition-colors flex items-center gap-1.5"
                        >
                            <span>Cari</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </button>
                    </form>
                </div>

                <!-- Action CTA Buttons (Visual Lengkap -> Klik Notice) -->
                <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                    <button 
                        type="button"
                        @click="openNotice" 
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-semibold shadow-xs flex items-center gap-2 transition-colors"
                    >
                        <Compass class="w-4 h-4" />
                        <span>Buka Direktori Karya</span>
                    </button>
                    <button 
                        type="button"
                        @click="openNotice" 
                        class="px-5 py-2.5 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 text-xs sm:text-sm font-semibold border border-slate-700 flex items-center gap-2 transition-colors"
                    >
                        <Users class="w-4 h-4" />
                        <span>Daftar Peneliti</span>
                    </button>
                    <button 
                        type="button"
                        @click="openNotice" 
                        class="px-4 py-2.5 rounded-xl text-slate-400 hover:text-white text-xs sm:text-sm font-medium flex items-center gap-1.5 transition-colors"
                    >
                        <BarChart3 class="w-4 h-4" />
                        <span>Lihat Statistik</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- 2. Statistics Counter Ribbon -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <FileText class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total Publikasi</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ stats.total_publikasi || 0 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <FlaskConical class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Penelitian</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ stats.total_penelitian || 0 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <HandHeart class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Pengabdian (PKM)</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ stats.total_pkm || 0 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <Users class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Dosen & Peneliti</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ stats.total_peneliti || 0 }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Five Research Pillars -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">5 Pilar Output Akademik</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Klasifikasi Luaran Riset & Inovasi</h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Setiap karya ilmiah terdokumentasi dengan nomor register, relasi multi-peneliti, tautan bukti digital, dan dokumen laporan resmi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- 1. Penelitian -->
                <button 
                    type="button"
                    @click="openNotice"
                    class="text-left bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-xs transition-all group flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-2.5">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <FlaskConical class="w-5 h-5" />
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Penelitian</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Riset dasar, terapan, dan pengembangan dengan skema hibah pembinaan & kompetitif.
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-emerald-700 pt-3 border-t border-slate-100 w-full">
                        <span>{{ stats.total_penelitian || 0 }} Karya</span>
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </button>

                <!-- 2. Publikasi -->
                <button 
                    type="button"
                    @click="openNotice"
                    class="text-left bg-white rounded-xl p-5 border border-slate-200 hover:border-blue-300 hover:shadow-xs transition-all group flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-2.5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <FileText class="w-5 h-5" />
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Publikasi Ilmiah</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Artikel pada Jurnal Nasional SINTA 1–6, Jurnal Internasional Scopus, dan Seminar Prosiding.
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-blue-700 pt-3 border-t border-slate-100 w-full">
                        <span>{{ stats.total_publikasi || 0 }} Artikel</span>
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </button>

                <!-- 3. PKM -->
                <button 
                    type="button"
                    @click="openNotice"
                    class="text-left bg-white rounded-xl p-5 border border-slate-200 hover:border-amber-300 hover:shadow-xs transition-all group flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-2.5">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <HandHeart class="w-5 h-5" />
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition-colors">Pengabdian (PKM)</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Penerapan iptek, pemberdayaan masyarakat, desa binaan, dan pelatihan tematik.
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-amber-700 pt-3 border-t border-slate-100 w-full">
                        <span>{{ stats.total_pkm || 0 }} Kegiatan</span>
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </button>

                <!-- 4. Buku -->
                <button 
                    type="button"
                    @click="openNotice"
                    class="text-left bg-white rounded-xl p-5 border border-slate-200 hover:border-violet-300 hover:shadow-xs transition-all group flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-2.5">
                        <div class="w-10 h-10 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <BookOpen class="w-5 h-5" />
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-violet-700 transition-colors">Buku & Monograf</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Buku ajar, referensi, monograf, dan modul perkuliahan ber-ISBN terdaftar.
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-violet-700 pt-3 border-t border-slate-100 w-full">
                        <span>{{ stats.total_buku || 0 }} Buku</span>
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </button>

                <!-- 5. HKI & Paten -->
                <button 
                    type="button"
                    @click="openNotice"
                    class="text-left bg-white rounded-xl p-5 border border-slate-200 hover:border-cyan-300 hover:shadow-xs transition-all group flex flex-col justify-between space-y-4"
                >
                    <div class="space-y-2.5">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <Award class="w-5 h-5" />
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-cyan-700 transition-colors">HKI & Paten</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pencatatan Hak Cipta, paten sederhana, program komputer, dan karya inovasi.
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-cyan-700 pt-3 border-t border-slate-100 w-full">
                        <span>{{ stats.total_haki || 0 }} HKI</span>
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </button>
            </div>
        </section>

        <!-- 4. Features / Why APIK -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Keunggulan Sistem</span>
                <h2 class="text-2xl font-bold text-slate-900">Dirancang untuk Efisiensi & Transparansi Riset</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs sm:text-sm">
                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <Search class="w-4 h-4" />
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Pencarian Terpadu & Multi-Filter</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Pencarian judul, nama dosen, nomor SK, ISBN, hingga pemfilteran berdasarkan tahun dan indeks SINTA/Scopus.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <Users class="w-4 h-4" />
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Profil Peneliti & Multi-Author</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Mendukung kolaborasi banyak dosen per karya ilmiah dengan pencatatan peran dan rekam jejak portofolio.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                        <BarChart3 class="w-4 h-4" />
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Analitik & Statistik Visual</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Visualisasi grafik tren tahunan dan proporsi publikasi bereputasi untuk pelaporan akreditasi dan evaluasi pimpinan.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. Bottom CTA Box -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="bg-[#0F172A] rounded-2xl p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="space-y-2 text-center md:text-left z-10">
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Ingin Menelusuri Seluruh Karya Ilmiah?</h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-lg">
                        Gunakan Direktori Karya APIK untuk menemukan publikasi, buku, paten, dan laporan pengabdian masyarakat secara lengkap.
                    </p>
                </div>

                <div class="flex items-center gap-3 z-10 shrink-0">
                    <button 
                        type="button"
                        @click="openNotice" 
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-semibold shadow-xs transition-colors flex items-center gap-1.5"
                    >
                        <span>Jelajahi Direktori</span>
                        <ArrowRight class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </section>

        <!-- Notice Modal -->
        <Modal :show="noticeOpen" @close="noticeOpen = false" maxWidth="sm">
            <template #title>Portal Layanan APIK</template>
            <template #content>
                <div class="space-y-3 text-xs text-slate-600 text-center py-2">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
                        <ShieldAlert class="w-6 h-6" />
                    </div>
                    <p class="font-bold text-slate-800 text-sm">Dalam Pengembangan</p>
                    <p class="text-slate-500 leading-relaxed">
                        Fitur ini sedang dalam proses sinkronisasi dan integrasi data internal LP2M.
                    </p>
                </div>
            </template>
        </Modal>
    </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Modal from '@/Components/Modal.vue';
import { 
    Sparkles, Search, ArrowRight, Compass, Users, BarChart3, 
    FlaskConical, FileText, HandHeart, BookOpen, Award, ShieldAlert 
} from 'lucide-vue-next';

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
});

const quickSearch = ref('');
const noticeOpen = ref(false);

const openNotice = () => {
    noticeOpen.value = true;
};
</script>
