<template>
    <PublicLayout>
        <Head title="Direktori Karya Ilmiah & Riset" />

        <!-- Header Compact Banner (Deep Navy Simple) -->
        <section class="bg-[#0F172A] text-white py-8 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
            <div class="max-w-7xl mx-auto space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Direktori Riset & Publikasi Ilmiah</h1>
                        <p class="text-xs sm:text-sm text-slate-300 mt-1">
                            Pusat data terintegrasi luaran Penelitian, Publikasi Jurnal/Prosiding, PKM, Buku, dan HKI/Paten.
                        </p>
                    </div>

                    <!-- Compact Counter Stats -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-300">
                            Total Luaran: <strong class="text-white font-bold">{{ stats.total_karya }}</strong>
                        </span>
                        <span class="px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-300">
                            Peneliti: <strong class="text-white font-bold">{{ stats.total_peneliti }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Integrated Search Form -->
                <form @submit.prevent="handleSearch" class="relative max-w-3xl">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5 pointer-events-none" />
                    <input 
                        type="text" 
                        v-model="searchForm.search"
                        placeholder="Cari judul karya ilmiah, nama peneliti/dosen, nomor SK, atau ISBN..."
                        class="w-full pl-10 pr-24 py-2.5 bg-slate-900 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <button 
                        type="submit" 
                        class="absolute right-1.5 top-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition-colors"
                    >
                        Cari
                    </button>
                </form>
            </div>
        </section>

        <!-- Main Content -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
            <!-- Filter Bar Card -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-2xs space-y-3">
                <!-- Module Category Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                    <button 
                        v-for="cat in moduleCategories" 
                        :key="cat.id"
                        @click="setModuleType(cat.id)"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all flex items-center gap-1.5',
                            searchForm.type === cat.id 
                                ? 'bg-blue-600 text-white shadow-xs' 
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                        ]"
                    >
                        <component :is="cat.icon" class="w-3.5 h-3.5" />
                        {{ cat.name }}
                        <span 
                            class="text-[10px] px-1.5 py-0.2 rounded-full font-mono"
                            :class="searchForm.type === cat.id ? 'bg-blue-800 text-white' : 'bg-slate-200 text-slate-700'"
                        >
                            {{ getCategoryCount(cat.id) }}
                        </span>
                    </button>
                </div>

                <!-- Secondary Filter & Summary -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2.5 border-t border-slate-100 text-xs">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <label class="text-slate-500 font-medium">Tahun:</label>
                            <select 
                                v-model="searchForm.year" 
                                @change="handleSearch"
                                class="px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                            >
                                <option value="">Semua Tahun</option>
                                <option v-for="yr in years" :key="yr" :value="yr">{{ yr }}</option>
                            </select>
                        </div>

                        <div v-if="['semua', 'publikasi', 'jurnal', 'prosiding'].includes(searchForm.type)" class="flex items-center gap-1.5">
                            <label class="text-slate-500 font-medium">Indeksasi:</label>
                            <select 
                                v-model="searchForm.index_cat" 
                                @change="handleSearch"
                                class="px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none"
                            >
                                <option value="">Semua Indeks</option>
                                <option value="Scopus">Scopus</option>
                                <option value="SINTA 1">SINTA 1</option>
                                <option value="SINTA 2">SINTA 2</option>
                                <option value="SINTA 3">SINTA 3</option>
                                <option value="SINTA 4">SINTA 4</option>
                                <option value="SINTA 5">SINTA 5</option>
                                <option value="SINTA 6">SINTA 6</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-slate-500">
                            Ditemukan <strong class="text-slate-900 font-bold">{{ items.length }}</strong> data
                        </span>
                        <button 
                            v-if="hasActiveFilter" 
                            @click="resetFilters" 
                            class="text-rose-600 hover:text-rose-700 font-semibold flex items-center gap-1 hover:underline ml-2"
                        >
                            <RotateCcw class="w-3 h-3" />
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Complete Information Result Cards -->
            <div v-if="items.length > 0" class="space-y-3">
                <article 
                    v-for="item in items" 
                    :key="`${item.type}-${item.id}`"
                    class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 hover:border-blue-300 hover:shadow-xs transition-all space-y-2.5"
                >
                    <!-- Top Row: Badges & Direct Action Links -->
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <!-- Module Type Badge -->
                            <span 
                                class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold border"
                                :class="getBadgeStyle(item.type)"
                            >
                                {{ item.type_label }}
                            </span>

                            <!-- Year -->
                            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                {{ item.year }}
                            </span>

                            <!-- Number / SINTA / ISBN / SK Identifier -->
                            <span v-if="item.nomor" class="px-2 py-0.5 rounded text-[11px] font-mono text-slate-600 bg-slate-50 border border-slate-200">
                                {{ item.nomor }}
                            </span>
                        </div>

                        <!-- Direct Action Buttons -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a 
                                v-if="item.link_url" 
                                :href="item.link_url" 
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold border border-blue-200 transition-colors"
                            >
                                <ExternalLink class="w-3.5 h-3.5" />
                                <span>Buka Tautan</span>
                            </a>

                            <a 
                                v-if="item.file_path" 
                                :href="`/storage/${item.file_path}`" 
                                target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold border border-emerald-200 transition-colors"
                            >
                                <Download class="w-3.5 h-3.5" />
                                <span>Unduh PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                        {{ item.title }}
                    </h2>

                    <!-- Meta Information (Skema, Jurnal, Penerbit, Pemegang Hak) -->
                    <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                        <span class="text-slate-400 font-normal">Keterangan:</span>
                        <span class="text-slate-700">{{ item.meta_info }}</span>
                    </div>

                    <!-- Contributors List with Roles -->
                    <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-400 font-medium">Peneliti / Penulis:</span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <Link 
                                v-for="contrib in item.contributors" 
                                :key="contrib.id"
                                :href="`/peneliti/${contrib.id}`"
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 font-medium transition-colors"
                            >
                                <User class="w-3 h-3 text-slate-400" />
                                <span>{{ contrib.name }}</span>
                                <span v-if="contrib.role && (contrib.role.includes('Ketua') || contrib.role.includes('Pertama'))" class="text-[10px] text-blue-600 font-bold">
                                    ({{ contrib.role }})
                                </span>
                            </Link>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-xl p-10 text-center border border-slate-200 text-xs space-y-2">
                <p class="font-bold text-slate-800 text-sm">Tidak ada data karya yang sesuai pencarian/filter.</p>
                <p class="text-slate-500">Silakan ubah kata kunci atau tekan tombol reset di bawah.</p>
                <button 
                    @click="resetFilters" 
                    class="mt-2 px-3.5 py-1.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500 transition-colors"
                >
                    Tampilkan Semua
                </button>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search, ExternalLink, Download, User, RotateCcw, 
    Compass, FlaskConical, FileText, HandHeart, BookOpen, Award 
} from 'lucide-vue-next';

const props = defineProps({
    items: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({}) },
    years: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const searchForm = reactive({
    search: props.filters.search || '',
    type: props.filters.type || 'semua',
    year: props.filters.year || '',
    index_cat: props.filters.index_cat || '',
});

const moduleCategories = [
    { id: 'semua', name: 'Semua', icon: Compass },
    { id: 'penelitian', name: 'Penelitian', icon: FlaskConical },
    { id: 'publikasi', name: 'Publikasi', icon: FileText },
    { id: 'pkm', name: 'PKM', icon: HandHeart },
    { id: 'buku', name: 'Buku', icon: BookOpen },
    { id: 'haki', name: 'HKI & Paten', icon: Award },
];

const getCategoryCount = (id) => {
    switch (id) {
        case 'semua': return props.stats.total_karya || 0;
        case 'penelitian': return props.stats.total_penelitian || 0;
        case 'publikasi': return props.stats.total_publikasi || 0;
        case 'pkm': return props.stats.total_pkm || 0;
        case 'buku': return props.stats.total_buku || 0;
        case 'haki': return props.stats.total_haki || 0;
        default: return 0;
    }
};

const hasActiveFilter = computed(() => {
    return searchForm.search !== '' || searchForm.type !== 'semua' || searchForm.year !== '' || searchForm.index_cat !== '';
});

const handleSearch = () => {
    router.get('/', {
        search: searchForm.search || undefined,
        type: searchForm.type !== 'semua' ? searchForm.type : undefined,
        year: searchForm.year || undefined,
        index_cat: searchForm.index_cat || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const setModuleType = (typeId) => {
    searchForm.type = typeId;
    handleSearch();
};

const resetFilters = () => {
    searchForm.search = '';
    searchForm.type = 'semua';
    searchForm.year = '';
    searchForm.index_cat = '';
    handleSearch();
};

const getBadgeStyle = (type) => {
    switch (type) {
        case 'penelitian': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'publikasi': return 'bg-blue-50 text-blue-700 border-blue-200';
        case 'pkm': return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'buku': return 'bg-violet-50 text-violet-700 border-violet-200';
        case 'haki': return 'bg-cyan-50 text-cyan-700 border-cyan-200';
        default: return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>
