<template>
    <AdminLayout>
        <template #header>Dashboard Pengelola</template>
        <Head title="Admin Dashboard" />

        <div class="space-y-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-[#0F172A] to-slate-800 text-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-700/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative overflow-hidden">
                <div class="space-y-2 relative z-10">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold">
                        <Sparkles class="w-3.5 h-3.5" />
                        Selamat Datang di APIK
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                        Halo, {{ $page.props.auth?.user?.name || 'Administrator' }}!
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                        Kelola data master peneliti dan catat output ilmiah (Penelitian, Buku, PKM, HKI, Publikasi) sivitas akademika dalam satu basis data terpusat.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2 relative z-10 shrink-0">
                    <Link 
                        href="/admin/penelitian"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5"
                    >
                        <Plus class="w-4 h-4" />
                        Input Penelitian
                    </Link>
                    <Link 
                        href="/admin/publikasi"
                        class="px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5"
                    >
                        <Plus class="w-4 h-4" />
                        Input Publikasi
                    </Link>
                </div>
            </div>

            <!-- Stats Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <Link href="/admin/peneliti" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-blue-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <Users class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-slate-500">Peneliti</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.peneliti }}</p>
                </Link>

                <Link href="/admin/penelitian" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-emerald-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <FlaskConical class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-emerald-600">Penelitian</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.penelitian }}</p>
                </Link>

                <Link href="/admin/publikasi" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-blue-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <FileText class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-blue-600">Publikasi</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.publikasi }}</p>
                </Link>

                <Link href="/admin/pkm" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-amber-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <HandHeart class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-amber-600">PKM</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.pkm }}</p>
                </Link>

                <Link href="/admin/buku" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-violet-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <BookOpen class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-violet-600">Buku</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.buku }}</p>
                </Link>

                <Link href="/admin/haki" class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-cyan-300 transition-all text-center group">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <Award class="w-4 h-4" />
                    </div>
                    <p class="text-xs font-semibold text-cyan-600">HKI / Paten</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ counts.haki }}</p>
                </Link>
            </div>

            <!-- Recent Activity Outputs -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Entri Luaran Terbaru</h3>
                        <p class="text-xs text-slate-500">Karya ilmiah yang baru-baru ini tercatat dalam sistem</p>
                    </div>
                    <Link href="/" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline">
                        Lihat Portal Publik →
                    </Link>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    <div v-for="(out, idx) in latestOutputs" :key="idx" class="py-3 flex items-start justify-between gap-4">
                        <div class="space-y-0.5 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ out.type }}
                                </span>
                                <span class="text-slate-400 font-mono">{{ out.year }}</span>
                                <span class="text-slate-400">• {{ out.created_at }}</span>
                            </div>
                            <h4 class="font-bold text-slate-800 truncate">{{ out.title }}</h4>
                            <p class="text-slate-500 truncate">{{ out.author || 'Kontributor Institusi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Sparkles, Plus, Users, FlaskConical, FileText, 
    HandHeart, BookOpen, Award 
} from 'lucide-vue-next';

defineProps({
    counts: { type: Object, required: true },
    latestOutputs: { type: Array, default: () => [] },
});
</script>
