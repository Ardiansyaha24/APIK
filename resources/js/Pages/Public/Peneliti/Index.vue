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
                <!-- Search and Prodi Dropdown -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="sm:col-span-7 relative">
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
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-xs bg-slate-200 rounded-full w-4 h-4 flex items-center justify-center"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Prodi Select -->
                    <div class="sm:col-span-5">
                        <select 
                            v-model="filtersForm.prodi"
                            @change="handleFilter"
                            class="w-full px-3.5 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">Semua Program Studi / Jurusan</option>
                            <option v-for="p in prodis" :key="p.id" :value="p.id">
                                {{ p.nama }} ({{ p.fakultas?.kode || 'Fakultas' }})
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Sort & View Controls -->
                <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 shrink-0">
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
                    <!-- Visual Top Ring Accent -->
                    <div class="space-y-4">
                        <!-- Top Author Profile Strip -->
                        <div class="flex items-start gap-3.5">
                            <!-- Distinctive Avatar: Monogram Badge with Glow -->
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

                        <!-- Department & Affiliation Capsule -->
                        <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <p class="font-semibold text-slate-800 truncate">{{ p.prodi?.nama || 'Program Studi' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ p.prodi?.fakultas?.nama || 'IAIN Manado' }}</p>
                        </div>

                        <!-- Focus Field / Tags -->
                        <div v-if="p.bidang_keahlian" class="space-y-1.5">
                            <div class="flex flex-wrap gap-1.5">
                                <span 
                                    v-for="(tag, tIdx) in p.bidang_keahlian.split(',').slice(0, 3)" 
                                    :key="tIdx"
                                    class="inline-flex items-center text-[10px] font-medium text-slate-600 bg-slate-100/90 hover:bg-slate-200/80 px-2 py-0.5 rounded-md transition-colors"
                                >
                                    {{ tag.trim() }}
                                </span>
                            </div>
                        </div>

                        <!-- Innovative Portfolio Spectrum Bar -->
                        <div class="pt-2 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[11px] font-medium text-slate-500">Portofolio Luaran:</span>
                                <span class="font-bold text-slate-900 font-mono">
                                    {{ getGrandTotal(p) }} <span class="font-normal text-[11px] text-slate-400">Total Karya</span>
                                </span>
                            </div>

                            <!-- Stacked Multi-color Spectrum Bar -->
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden flex" title="Komposisi Karya">
                                <div 
                                    v-if="p.penelitians_count" 
                                    :style="{ width: getPercentage(p.penelitians_count, getGrandTotal(p)) + '%' }" 
                                    class="bg-emerald-500 h-full" 
                                    title="Penelitian"
                                ></div>
                                <div 
                                    v-if="p.publikasis_count" 
                                    :style="{ width: getPercentage(p.publikasis_count, getGrandTotal(p)) + '%' }" 
                                    class="bg-blue-500 h-full" 
                                    title="Publikasi"
                                ></div>
                                <div 
                                    v-if="p.pkms_count" 
                                    :style="{ width: getPercentage(p.pkms_count, getGrandTotal(p)) + '%' }" 
                                    class="bg-amber-500 h-full" 
                                    title="PKM"
                                ></div>
                                <div 
                                    v-if="p.bukus_count" 
                                    :style="{ width: getPercentage(p.bukus_count, getGrandTotal(p)) + '%' }" 
                                    class="bg-violet-500 h-full" 
                                    title="Buku"
                                ></div>
                                <div 
                                    v-if="p.hakis_count" 
                                    :style="{ width: getPercentage(p.hakis_count, getGrandTotal(p)) + '%' }" 
                                    class="bg-cyan-500 h-full" 
                                    title="HKI"
                                ></div>
                            </div>

                            <!-- Elegant Indicator Chips -->
                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ p.penelitians_count }} Riset</span>
                                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>{{ p.publikasis_count }} Jurnal</span>
                                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>{{ p.pkms_count }} PKM</span>
                                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>{{ p.bukus_count }} Buku</span>
                                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>{{ p.hakis_count }} HKI</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card CTA Action -->
                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <Link 
                            :href="`/peneliti/${p.id}`" 
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-all duration-200 group-hover:shadow-md group-hover:shadow-blue-500/20"
                        >
                            <span>Eksplorasi Profil & Rekam Jejak</span>
                            <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </article>
            </div>

            <!-- VIEW 2: List / Table Matrix Mode (Fast Academic Directory) -->
            <div v-else-if="viewMode === 'list' && filteredAndSortedPenelitis.length > 0" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-4">Nama Peneliti & Dosen</th>
                                <th class="py-3.5 px-4">Program Studi</th>
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
                                    <p class="font-medium text-slate-800">{{ p.prodi?.nama || '-' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ p.prodi?.fakultas?.kode || '' }}</p>
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
                    Silakan ubah kata kunci pencarian atau sesuaikan pilihan program studi.
                </p>
                <button 
                    @click="resetAllFilters"
                    class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition-colors cursor-pointer"
                >
                    Reset Filter
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
    prodis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const sortBy = ref('karya');
const viewMode = ref('grid');

const filtersForm = reactive({
    search: props.filters.search || '',
    prodi: props.filters.prodi || '',
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
        prodi: filtersForm.prodi || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetAllFilters = () => {
    filtersForm.search = '';
    filtersForm.prodi = '';
    handleFilter();
};
</script>
