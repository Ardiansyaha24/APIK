<template>
    <PublicLayout>
        <Head title="Daftar Peneliti & Dosen" />

        <!-- Header Compact Banner -->
        <section class="bg-[#0F172A] text-white py-6 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Daftar Peneliti & Dosen</h1>
                    <p class="text-xs text-slate-300">Pangkalan data profil akademik, bidang keahlian, dan portofolio luaran riset.</p>
                </div>
                <div class="text-xs text-slate-400">
                    Total Dosen: <strong class="text-white font-bold">{{ penelitis.length }}</strong>
                </div>
            </div>
        </section>

        <!-- Main Explorer -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">
            <!-- Filter & Search Card -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-2xs space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2 relative">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" />
                        <input 
                            type="text" 
                            v-model="filtersForm.search"
                            @input="handleFilter"
                            placeholder="Cari nama dosen, NIDN, atau bidang fokus riset..."
                            class="w-full pl-9 pr-3 py-1.5 bg-slate-50 rounded-lg border border-slate-200 text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                    </div>

                    <div>
                        <select 
                            v-model="filtersForm.prodi"
                            @change="handleFilter"
                            class="w-full px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-200 text-xs focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                        >
                            <option value="">Semua Program Studi</option>
                            <option v-for="p in prodis" :key="p.id" :value="p.id">
                                {{ p.nama }} ({{ p.fakultas?.kode || 'Fakultas' }})
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Alphabet A-Z Quick Selector -->
                <div class="pt-2 border-t border-slate-100 flex items-center gap-1 overflow-x-auto pb-1 scrollbar-none text-xs">
                    <button 
                        @click="setHuruf('')"
                        :class="[
                            'px-2.5 py-0.5 rounded text-xs font-semibold shrink-0 transition-colors',
                            filtersForm.huruf === '' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                        ]"
                    >
                        Semua
                    </button>
                    <button 
                        v-for="letter in alphabet" 
                        :key="letter"
                        @click="setHuruf(letter)"
                        :class="[
                            'w-6 h-6 rounded text-xs font-semibold shrink-0 transition-colors flex items-center justify-center',
                            filtersForm.huruf === letter ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                        ]"
                    >
                        {{ letter }}
                    </button>
                </div>
            </div>

            <!-- Peneliti Cards Grid -->
            <div v-if="penelitis.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <article 
                    v-for="p in penelitis" 
                    :key="p.id"
                    class="bg-white rounded-xl p-4 border border-slate-200 hover:border-blue-300 hover:shadow-xs transition-all flex flex-col justify-between space-y-3"
                >
                    <div class="space-y-2">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600 text-white font-bold text-sm flex items-center justify-center shrink-0">
                                {{ p.nama_lengkap.charAt(0) }}
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug line-clamp-2">
                                    {{ p.nama_lengkap }}
                                </h2>
                                <p class="text-[11px] text-slate-500 font-mono">NIDN: {{ p.nidn || '-' }}</p>
                            </div>
                        </div>

                        <div class="px-2.5 py-1 rounded bg-slate-50 border border-slate-100 text-xs text-slate-600">
                            <p class="font-semibold text-slate-700 truncate">{{ p.prodi?.nama || 'Prodi' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ p.prodi?.fakultas?.nama || '' }}</p>
                        </div>

                        <p v-if="p.bidang_keahlian" class="text-[11px] text-slate-500 line-clamp-2">
                            <span class="font-semibold text-slate-700">Keahlian:</span> {{ p.bidang_keahlian }}
                        </p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                        <span class="text-slate-500">
                            Total Karya: <strong class="text-blue-600 font-bold font-mono">{{ p.penelitians_count + p.bukus_count + p.pkms_count + p.hakis_count + p.publikasis_count }}</strong>
                        </span>

                        <Link 
                            :href="`/peneliti/${p.id}`" 
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-500 transition-colors"
                        >
                            <span>Profil</span>
                            <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-xl p-8 text-center border border-slate-200 text-xs text-slate-500">
                Tidak ada peneliti yang sesuai pencarian.
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Search, ArrowRight } from 'lucide-vue-next';

const props = defineProps({
    penelitis: { type: Array, default: () => [] },
    prodis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

const filtersForm = reactive({
    search: props.filters.search || '',
    prodi: props.filters.prodi || '',
    huruf: props.filters.huruf || '',
});

const handleFilter = () => {
    router.get('/peneliti', {
        search: filtersForm.search || undefined,
        prodi: filtersForm.prodi || undefined,
        huruf: filtersForm.huruf || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const setHuruf = (char) => {
    filtersForm.huruf = char;
    handleFilter();
};
</script>
