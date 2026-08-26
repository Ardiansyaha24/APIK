<template>
    <PublicLayout>
        <Head title="Direktori Karya Ilmiah & Riset — APIK IAIN Manado" />

        <!-- Header Section: Modern Scientific Repository Banner -->
        <section class="bg-slate-900 text-white pt-10 pb-14 px-4 sm:px-6 lg:px-8 relative overflow-hidden border-b border-slate-800">
            <!-- Ambient Radial Glows -->
            <div class="absolute -top-24 right-10 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto relative z-10 space-y-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-2.5 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-xs font-semibold text-blue-400">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Pusat Data Luaran Ilmiah & Pengabdian Terpadu</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                            Direktori Karya & Publikasi
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Pangkalan data repositori karya ilmiah dosen IAIN Manado yang mencakup laporan penelitian, artikel jurnal bereputasi, pengabdian masyarakat, buku, dan sertifikat HKI.
                        </p>
                    </div>

                    <!-- Modern Aggregate Metrics Capsule -->
                    <div class="flex items-center gap-4 bg-slate-800/80 backdrop-blur-md border border-slate-700/70 rounded-2xl p-3.5 sm:px-5 shrink-0 shadow-lg self-start md:self-auto">
                        <div class="text-center px-1">
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Total Luaran</p>
                            <p class="text-lg sm:text-xl font-extrabold text-white font-mono mt-0.5">{{ stats.total_karya || 0 }} <span class="text-xs font-normal text-slate-400">Karya</span></p>
                        </div>
                        <div class="h-7 w-px bg-slate-700"></div>
                        <div class="text-center px-1">
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Peneliti Terlibat</p>
                            <p class="text-lg sm:text-xl font-extrabold text-emerald-400 font-mono mt-0.5">{{ stats.total_peneliti || 0 }} <span class="text-xs font-normal text-slate-400">Dosen</span></p>
                        </div>
                    </div>
                </div>

                <!-- 5 Pillar Category Tabs (Modern Segmented Navigation) -->
                <div class="pt-2 flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none text-xs">
                    <button 
                        v-for="cat in moduleCategories" 
                        :key="cat.id"
                        @click="setModuleType(cat.id)"
                        :class="[
                            'px-3.5 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all cursor-pointer flex items-center gap-2 border',
                            searchForm.type === cat.id 
                                ? getActiveCategoryTabStyle(cat.id)
                                : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700 border-slate-700/60'
                        ]"
                    >
                        <component :is="cat.icon" class="w-3.5 h-3.5" />
                        <span>{{ cat.name }}</span>
                        <span 
                            class="text-[10px] px-1.5 py-0.5 rounded-full font-mono font-bold"
                            :class="searchForm.type === cat.id ? 'bg-white/20 text-white' : 'bg-slate-700 text-slate-400'"
                        >
                            {{ getCategoryCount(cat.id) }}
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Main Content Area -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Filter & Search Toolbar -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <!-- Search Input -->
                    <div class="sm:col-span-6 lg:col-span-7 relative">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" />
                        <input 
                            type="text" 
                            v-model="searchForm.search"
                            @input="handleSearch"
                            placeholder="Cari judul karya ilmiah, nama peneliti, nomor SK, ISBN, atau DOI..."
                            class="w-full pl-10 pr-8 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                        />
                        <button 
                            v-if="searchForm.search" 
                            @click="searchForm.search = ''; handleSearch()"
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-xs bg-slate-200 rounded-full w-4 h-4 flex items-center justify-center"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Year Dropdown -->
                    <div class="sm:col-span-3 lg:col-span-2">
                        <select 
                            v-model="searchForm.year" 
                            @change="handleSearch"
                            class="w-full px-3 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">Semua Tahun</option>
                            <option v-for="yr in years" :key="yr" :value="yr">{{ yr }}</option>
                        </select>
                    </div>

                    <!-- Indeksasi Filter (Only for Publikasi) -->
                    <div v-if="['semua', 'publikasi', 'jurnal', 'prosiding'].includes(searchForm.type)" class="sm:col-span-3 lg:col-span-3">
                        <select 
                            v-model="searchForm.index_cat" 
                            @change="handleSearch"
                            class="w-full px-3 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">Semua Indeksasi</option>
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

                <!-- Secondary Sub-toolbar: Sort, View, Result Info -->
                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <span class="text-slate-500">
                            Menampilkan <strong class="text-slate-900 font-bold font-mono">{{ sortedItems.length }}</strong> karya ilmiah terdata
                        </span>
                        <button 
                            v-if="hasActiveFilter" 
                            @click="resetFilters" 
                            class="text-rose-600 hover:text-rose-700 font-semibold inline-flex items-center gap-1 hover:underline cursor-pointer"
                        >
                            <RotateCcw class="w-3 h-3" />
                            Reset Filter
                        </button>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        <!-- Sort Select -->
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <SlidersHorizontal class="w-3.5 h-3.5 text-slate-400" />
                            <select 
                                v-model="sortBy"
                                class="px-2.5 py-1 bg-slate-50 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 cursor-pointer"
                            >
                                <option value="terbaru">Tahun Terbaru</option>
                                <option value="judul">Judul (A-Z)</option>
                            </select>
                        </div>

                        <!-- View Switcher -->
                        <div class="flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200/60">
                            <button 
                                @click="viewMode = 'card'"
                                :class="[
                                    'p-1.5 rounded-md transition-all cursor-pointer',
                                    viewMode === 'card' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'
                                ]"
                                title="Tampilan Kartu"
                            >
                                <LayoutGrid class="w-3.5 h-3.5" />
                            </button>
                            <button 
                                @click="viewMode = 'table'"
                                :class="[
                                    'p-1.5 rounded-md transition-all cursor-pointer',
                                    viewMode === 'table' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'
                                ]"
                                title="Tampilan Tabel"
                            >
                                <List class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW 1: Modern Scientific Dossier Cards Feed -->
            <div v-if="viewMode === 'card' && sortedItems.length > 0" class="space-y-4">
                <article 
                    v-for="item in sortedItems" 
                    :key="`${item.type}-${item.id}`"
                    class="group bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 hover:border-blue-400/80 hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-300 space-y-3.5 relative overflow-hidden"
                >
                    <!-- Top Ribbon Accent on Hover -->
                    <div 
                        class="absolute top-0 inset-x-0 h-1 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                        :class="getTopAccentColor(item.type)"
                    ></div>

                    <!-- Header Row: Category Badge + Year + Identification + Direct Actions -->
                    <div class="flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Pillar Icon Badge -->
                            <span 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border"
                                :class="getBadgeStyle(item.type)"
                            >
                                <component :is="getPillarIcon(item.type)" class="w-3.5 h-3.5" />
                                <span>{{ item.type_label }}</span>
                            </span>

                            <!-- Year Pill -->
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200/70">
                                {{ item.year }}
                            </span>

                            <!-- Registered Identifier / SK / ISBN / DOI -->
                            <span v-if="item.nomor" class="px-2.5 py-0.5 rounded-md text-xs font-mono text-slate-600 bg-slate-50 border border-slate-200/70">
                                {{ item.nomor }}
                            </span>
                        </div>

                        <!-- Quick Action Tautan & PDF -->
                        <div class="flex items-center gap-2 shrink-0">
                            <a 
                                v-if="item.link_url" 
                                :href="item.link_url" 
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold border border-blue-200/60 transition-all duration-200"
                            >
                                <ExternalLink class="w-3.5 h-3.5" />
                                <span>Tautan Sumber</span>
                            </a>

                            <a 
                                v-if="item.file_path" 
                                :href="`/storage/${item.file_path}`" 
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-semibold border border-emerald-200/60 transition-all duration-200"
                            >
                                <Download class="w-3.5 h-3.5" />
                                <span>Dokumen</span>
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ item.title }}
                    </h2>

                    <!-- Context Meta (Penerbit / Jurnal / Skema Bantuan) -->
                    <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50/70 p-2.5 rounded-xl border border-slate-100">
                        <Info class="w-4 h-4 text-slate-400 shrink-0" />
                        <span class="text-slate-700 font-medium truncate">{{ item.meta_info }}</span>
                    </div>

                    <!-- Contributors & Authors Strip -->
                    <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-400 font-medium flex items-center gap-1">
                            <User class="w-3.5 h-3.5" />
                            Peneliti / Penulis:
                        </span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <Link 
                                v-for="contrib in item.contributors" 
                                :key="contrib.id"
                                :href="`/peneliti/${contrib.id}`"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200/80 font-medium transition-colors"
                            >
                                <span class="font-semibold">{{ contrib.name }}</span>
                                <span v-if="contrib.role && (contrib.role.includes('Ketua') || contrib.role.includes('Pertama'))" class="text-[10px] text-blue-600 font-bold bg-blue-100/60 px-1 rounded">
                                    {{ contrib.role }}
                                </span>
                            </Link>
                        </div>
                    </div>
                </article>
            </div>

            <!-- VIEW 2: Dense Academic Table View -->
            <div v-else-if="viewMode === 'table' && sortedItems.length > 0" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-4">Pilar & Tahun</th>
                                <th class="py-3.5 px-4">Judul Karya Ilmiah</th>
                                <th class="py-3.5 px-4">Keterangan / Penerbit</th>
                                <th class="py-3.5 px-4">Peneliti Terlibat</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr 
                                v-for="item in sortedItems" 
                                :key="`${item.type}-${item.id}`"
                                class="hover:bg-blue-50/40 transition-colors"
                            >
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span 
                                            class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border"
                                            :class="getBadgeStyle(item.type)"
                                        >
                                            {{ item.type_label }}
                                        </span>
                                        <p class="font-mono text-slate-500 font-semibold">{{ item.year }}</p>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-900 leading-snug max-w-md">{{ item.title }}</p>
                                    <p v-if="item.nomor" class="text-[10px] text-slate-400 font-mono mt-0.5">{{ item.nomor }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 max-w-xs">
                                    <p class="truncate">{{ item.meta_info }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        <Link 
                                            v-for="contrib in item.contributors" 
                                            :key="contrib.id"
                                            :href="`/peneliti/${contrib.id}`"
                                            class="text-blue-600 hover:underline font-medium text-xs truncate"
                                        >
                                            {{ contrib.name }}
                                        </Link>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a 
                                            v-if="item.link_url" 
                                            :href="item.link_url" 
                                            target="_blank"
                                            class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors"
                                            title="Buka Tautan"
                                        >
                                            <ExternalLink class="w-3.5 h-3.5" />
                                        </a>
                                        <a 
                                            v-if="item.file_path" 
                                            :href="`/storage/${item.file_path}`" 
                                            target="_blank"
                                            class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors"
                                            title="Unduh Dokumen"
                                        >
                                            <Download class="w-3.5 h-3.5" />
                                        </a>
                                    </div>
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
                <h3 class="text-sm font-bold text-slate-800">Tidak ada karya yang sesuai</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Silakan ubah kata kunci pencarian atau pilih kategori pilar lainnya.
                </p>
                <button 
                    @click="resetFilters" 
                    class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition-colors cursor-pointer"
                >
                    Tampilkan Semua Karya
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
    Search, ExternalLink, Download, User, RotateCcw, 
    Compass, FlaskConical, FileText, HandHeart, BookOpen, Award,
    Sparkles, SlidersHorizontal, LayoutGrid, List, Info 
} from 'lucide-vue-next';

const props = defineProps({
    items: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({}) },
    years: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const sortBy = ref('terbaru');
const viewMode = ref('card');

const searchForm = reactive({
    search: props.filters.search || '',
    type: props.filters.type || 'semua',
    year: props.filters.year || '',
    index_cat: props.filters.index_cat || '',
});

const moduleCategories = [
    { id: 'semua', name: 'Semua Karya', icon: Compass },
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

const sortedItems = computed(() => {
    let list = [...props.items];

    if (sortBy.value === 'judul') {
        list.sort((a, b) => a.title.localeCompare(b.title));
    } else {
        list.sort((a, b) => (b.year || 0) - (a.year || 0));
    }

    return list;
});

const handleSearch = () => {
    router.get('/direktori', {
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

const getPillarIcon = (type) => {
    switch (type) {
        case 'penelitian': return FlaskConical;
        case 'publikasi': return FileText;
        case 'pkm': return HandHeart;
        case 'buku': return BookOpen;
        case 'haki': return Award;
        default: return Compass;
    }
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

const getTopAccentColor = (type) => {
    switch (type) {
        case 'penelitian': return 'bg-emerald-500';
        case 'publikasi': return 'bg-blue-500';
        case 'pkm': return 'bg-amber-500';
        case 'buku': return 'bg-violet-500';
        case 'haki': return 'bg-cyan-500';
        default: return 'bg-slate-500';
    }
};

const getActiveCategoryTabStyle = (id) => {
    switch (id) {
        case 'penelitian': return 'bg-emerald-600 text-white border-emerald-500 shadow-md shadow-emerald-600/20';
        case 'publikasi': return 'bg-blue-600 text-white border-blue-500 shadow-md shadow-blue-600/20';
        case 'pkm': return 'bg-amber-600 text-white border-amber-500 shadow-md shadow-amber-600/20';
        case 'buku': return 'bg-violet-600 text-white border-violet-500 shadow-md shadow-violet-600/20';
        case 'haki': return 'bg-cyan-600 text-white border-cyan-500 shadow-md shadow-cyan-600/20';
        default: return 'bg-white text-slate-900 border-white shadow-md shadow-white/10 font-bold';
    }
};
</script>
