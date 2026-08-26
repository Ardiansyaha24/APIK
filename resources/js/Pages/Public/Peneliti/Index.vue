<template>
    <PublicLayout>
        <Head title="Pakar & Peneliti — Direktori Akademik APIK IAIN Manado" />

        <!-- Hero Header: Clean Modern Scholar Banner -->
        <section class="bg-slate-900 text-white pt-10 pb-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden border-b border-slate-800">
            <!-- Ambient Radial Glows -->
            <div class="absolute -top-24 right-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 left-10 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-xs font-semibold text-blue-400">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Pangkalan Talenta & Riset Akademik</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                            Pakar & Peneliti
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Pusat jejaring kepakaran dosen dan peneliti IAIN Manado. Temukan kolaborator riset, rekam jejak keilmuan, dan portofolio luaran pengabdian.
                        </p>
                    </div>

                    <!-- Modern Stats Capsule -->
                    <div class="flex items-center gap-4 bg-slate-800/80 backdrop-blur-md border border-slate-700/70 rounded-2xl p-3.5 sm:px-5 shrink-0 shadow-lg self-start md:self-auto">
                        <div class="text-center px-1">
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Peneliti Aktif</p>
                            <p class="text-lg sm:text-xl font-extrabold text-white font-mono mt-0.5">{{ penelitis.length }} <span class="text-xs font-normal text-slate-400">Dosen</span></p>
                        </div>
                        <div class="h-7 w-px bg-slate-700"></div>
                        <div class="text-center px-1">
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Akumulasi Luaran</p>
                            <p class="text-lg sm:text-xl font-extrabold text-emerald-400 font-mono mt-0.5">{{ totalSemuaKarya }} <span class="text-xs font-normal text-slate-400">Karya</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Area -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Filter & Utility Toolbar -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-xl">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" />
                    <input 
                        type="text" 
                        v-model="filtersForm.search"
                        @input="handleFilter"
                        placeholder="Cari nama dosen, NIDN, atau fokus keahlian..."
                        class="w-full pl-10 pr-8 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                    />
                    <button 
                        v-if="filtersForm.search" 
                        @click="filtersForm.search = ''; handleFilter()"
                        class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-xs bg-slate-200 rounded-full w-4 h-4 flex items-center justify-center cursor-pointer"
                    >
                        &times;
                    </button>
                </div>

                <!-- Sort & View Controls -->
                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <SlidersHorizontal class="w-3.5 h-3.5 text-slate-400" />
                        <select 
                            v-model="sortBy"
                            class="px-2.5 py-1.5 bg-slate-50 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 cursor-pointer"
                        >
                            <option value="karya">Top Produktivitas</option>
                            <option value="nama">Nama (A-Z)</option>
                            <option value="penelitian">Riset Terbanyak</option>
                        </select>
                    </div>

                    <!-- View Mode Toggle (Grid vs Matrix/List) -->
                    <div class="flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200/60">
                        <button 
                            @click="viewMode = 'grid'"
                            :class="[
                                'p-1.5 rounded-md transition-all cursor-pointer',
                                viewMode === 'grid' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'
                            ]"
                            title="Tampilan Kartu"
                        >
                            <LayoutGrid class="w-4 h-4" />
                        </button>
                        <button 
                            @click="viewMode = 'list'"
                            :class="[
                                'p-1.5 rounded-md transition-all cursor-pointer',
                                viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'
                            ]"
                            title="Tampilan Daftar / Tabel"
                        >
                            <List class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- VIEW 1: Grid Mode (Executive Scholar Dossiers) -->
            <div v-if="viewMode === 'grid' && filteredAndSortedPenelitis.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <article 
                    v-for="p in filteredAndSortedPenelitis" 
                    :key="p.id"
                    class="group bg-white rounded-2xl p-5 border border-slate-200/90 hover:border-blue-400/80 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col justify-between relative overflow-hidden"
                >
                    <div class="space-y-4">
                        <!-- Top Author Profile Strip -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-900 via-slate-800 to-blue-900 text-white font-extrabold text-base flex items-center justify-center shrink-0 shadow-md shadow-slate-900/10 border border-slate-700/30 group-hover:scale-105 group-hover:border-blue-400/40 transition-all duration-300">
                                {{ p.nama_lengkap.charAt(0) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <Link 
                                    :href="`/peneliti/${p.id}`"
                                    class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2"
                                >
                                    {{ p.nama_lengkap }}
                                </Link>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-mono font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                        NIDN: {{ p.nidn || '-' }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                                        {{ p.status === 'aktif' ? 'Dosen Tetap' : 'Pakar' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Expertise Focus Tag -->
                        <div v-if="p.bidang_keahlian" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <p class="text-[10px] uppercase font-bold text-slate-400">Kepakaran</p>
                            <p class="font-medium text-slate-700 line-clamp-1 mt-0.5">{{ p.bidang_keahlian }}</p>
                        </div>

                        <!-- Output Spectrum Bar -->
                        <div class="space-y-1.5 pt-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-semibold text-slate-600">Portofolio Luaran</span>
                                <span class="font-mono font-extrabold text-slate-900">{{ getGrandTotal(p) }} Karya</span>
                            </div>

                            <!-- Stacked Progress Bar -->
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden flex">
                                <div 
                                    v-if="p.penelitians_count" 
                                    :style="{ width: `${getPercentage(p.penelitians_count, getGrandTotal(p))}%` }" 
                                    class="bg-emerald-500 h-full" 
                                    :title="`Penelitian: ${p.penelitians_count}`"
                                ></div>
                                <div 
                                    v-if="p.publikasis_count" 
                                    :style="{ width: `${getPercentage(p.publikasis_count, getGrandTotal(p))}%` }" 
                                    class="bg-blue-500 h-full" 
                                    :title="`Publikasi: ${p.publikasis_count}`"
                                ></div>
                                <div 
                                    v-if="p.pkms_count" 
                                    :style="{ width: `${getPercentage(p.pkms_count, getGrandTotal(p))}%` }" 
                                    class="bg-amber-500 h-full" 
                                    :title="`PKM: ${p.pkms_count}`"
                                ></div>
                                <div 
                                    v-if="p.bukus_count" 
                                    :style="{ width: `${getPercentage(p.bukus_count, getGrandTotal(p))}%` }" 
                                    class="bg-violet-500 h-full" 
                                    :title="`Buku: ${p.bukus_count}`"
                                ></div>
                                <div 
                                    v-if="p.hakis_count" 
                                    :style="{ width: `${getPercentage(p.hakis_count, getGrandTotal(p))}%` }" 
                                    class="bg-cyan-500 h-full" 
                                    :title="`HKI: ${p.hakis_count}`"
                                ></div>
                            </div>

                            <!-- 5 Pillar Tiny Matrix Badges -->
                            <div class="grid grid-cols-5 gap-1 text-center pt-2 text-[10px]">
                                <div class="p-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    <p class="text-[9px] font-bold uppercase text-emerald-600">Riset</p>
                                    <p class="font-mono font-extrabold">{{ p.penelitians_count }}</p>
                                </div>
                                <div class="p-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-100">
                                    <p class="text-[9px] font-bold uppercase text-blue-600">Artikel</p>
                                    <p class="font-mono font-extrabold">{{ p.publikasis_count }}</p>
                                </div>
                                <div class="p-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-100">
                                    <p class="text-[9px] font-bold uppercase text-amber-600">PKM</p>
                                    <p class="font-mono font-extrabold">{{ p.pkms_count }}</p>
                                </div>
                                <div class="p-1 rounded-lg bg-violet-50 text-violet-800 border border-violet-100">
                                    <p class="text-[9px] font-bold uppercase text-violet-600">Buku</p>
                                    <p class="font-mono font-extrabold">{{ p.bukus_count }}</p>
                                </div>
                                <div class="p-1 rounded-lg bg-cyan-50 text-cyan-800 border border-cyan-100">
                                    <p class="text-[9px] font-bold uppercase text-cyan-600">HKI</p>
                                    <p class="font-mono font-extrabold">{{ p.hakis_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Action -->
                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span v-if="p.sinta_id" class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600">SINTA</span>
                            <span v-if="p.scopus_id" class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-[9px] font-bold text-slate-600">SCOPUS</span>
                        </div>

                        <Link 
                            :href="`/peneliti/${p.id}`"
                            class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors"
                        >
                            <span>Lihat Portofolio</span>
                            <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                        </Link>
                    </div>
                </article>
            </div>

            <!-- VIEW 2: List / Academic Matrix Table Mode -->
            <div v-else-if="viewMode === 'list' && filteredAndSortedPenelitis.length > 0" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4">Nama Peneliti & NIDN</th>
                                <th class="py-3.5 px-4">Bidang Keahlian</th>
                                <th class="py-3.5 px-4 text-center">Penelitian</th>
                                <th class="py-3.5 px-4 text-center">Publikasi</th>
                                <th class="py-3.5 px-4 text-center">PKM</th>
                                <th class="py-3.5 px-4 text-center">Buku</th>
                                <th class="py-3.5 px-4 text-center">HKI</th>
                                <th class="py-3.5 px-4 text-center">Total</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr 
                                v-for="p in filteredAndSortedPenelitis" 
                                :key="p.id"
                                class="hover:bg-blue-50/40 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ p.nama_lengkap.charAt(0) }}
                                        </div>
                                        <div>
                                            <Link :href="`/peneliti/${p.id}`" class="font-bold text-slate-900 hover:text-blue-600">
                                                {{ p.nama_lengkap }}
                                            </Link>
                                            <p class="text-[10px] text-slate-400 font-mono">NIDN: {{ p.nidn || '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-medium text-slate-700 truncate max-w-xs">{{ p.bidang_keahlian || '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-emerald-700">{{ p.penelitians_count }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-blue-700">{{ p.publikasis_count }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-amber-700">{{ p.pkms_count }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-violet-700">{{ p.bukus_count }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-cyan-700">{{ p.hakis_count }}</td>
                                <td class="py-3.5 px-4 text-center font-mono font-extrabold text-slate-900 bg-slate-50/50">
                                    {{ getGrandTotal(p) }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <Link 
                                        :href="`/peneliti/${p.id}`"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-semibold transition-colors"
                                    >
                                        <span>Profil</span>
                                        <ArrowRight class="w-3 h-3" />
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <Search class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-bold text-slate-800">Tidak ada peneliti yang cocok</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Silakan ubah kata kunci pencarian dosen atau bidang keahlian.
                </p>
                <button 
                    @click="resetAllFilters"
                    class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition-colors cursor-pointer"
                >
                    Reset Pencarian
                </button>
            </div>
        </main>
    </PublicLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search, ArrowRight, Sparkles, 
    SlidersHorizontal, LayoutGrid, List 
} from 'lucide-vue-next';

const props = defineProps({
    penelitis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const sortBy = ref('karya');
const viewMode = ref('grid');

const filtersForm = reactive({
    search: props.filters.search || '',
});

const totalSemuaKarya = computed(() => {
    return props.penelitis.reduce((acc, p) => acc + getGrandTotal(p), 0);
});

const getGrandTotal = (p) => {
    return (p.penelitians_count || 0) + (p.bukus_count || 0) + (p.pkms_count || 0) + (p.hakis_count || 0) + (p.publikasis_count || 0);
};

const getPercentage = (count, total) => {
    if (!total || total === 0) return 0;
    return Math.round((count / total) * 100);
};

const filteredAndSortedPenelitis = computed(() => {
    let list = [...props.penelitis];

    if (sortBy.value === 'karya') {
        list.sort((a, b) => getGrandTotal(b) - getGrandTotal(a));
    } else if (sortBy.value === 'penelitian') {
        list.sort((a, b) => (b.penelitians_count || 0) - (a.penelitians_count || 0));
    } else {
        list.sort((a, b) => a.nama_lengkap.localeCompare(b.nama_lengkap));
    }

    return list;
});

const handleFilter = () => {
    router.get('/peneliti', {
        search: filtersForm.search || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetAllFilters = () => {
    filtersForm.search = '';
    handleFilter();
};
</script>
