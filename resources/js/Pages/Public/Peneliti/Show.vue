<template>
    <PublicLayout>
        <Head :title="`Profil Peneliti — ${peneliti.nama_lengkap}`" />

        <!-- Header Profile (Deep Navy) -->
        <section class="bg-[#0F172A] text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <!-- Avatar & Personal Info -->
                    <div class="flex items-start sm:items-center gap-5">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-extrabold text-3xl flex items-center justify-center shadow-xl shadow-blue-500/20 shrink-0 border border-blue-400/20">
                            {{ peneliti.nama_lengkap.charAt(0) }}
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">{{ peneliti.nama_lengkap }}</h1>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                    {{ peneliti.status === 'aktif' ? 'Dosen Aktif' : 'Purna Tugas' }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-400 font-mono">
                                NIDN / NIP: <span class="text-slate-200">{{ peneliti.nidn || '-' }}</span>
                            </p>

                            <p v-if="peneliti.bidang_keahlian" class="text-xs text-blue-300/90 pt-1">
                                <span class="text-slate-400 font-medium">Keahlian:</span> {{ peneliti.bidang_keahlian }}
                            </p>
                        </div>
                    </div>

                    <!-- External Profile Badges (SINTA, Scopus, Google Scholar, ORCID) -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a 
                            v-if="peneliti.sinta_id" 
                            :href="`https://sinta.kemdikbud.go.id/authors/profile/${peneliti.sinta_id}`" 
                            target="_blank"
                            class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-medium text-slate-200 flex items-center gap-1.5 transition-colors"
                        >
                            <ExternalLink class="w-3.5 h-3.5 text-blue-400" />
                            <span>SINTA ID</span>
                        </a>

                        <a 
                            v-if="peneliti.scopus_id" 
                            :href="`https://www.scopus.com/authid/detail.uri?authorId=${peneliti.scopus_id}`" 
                            target="_blank"
                            class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-medium text-slate-200 flex items-center gap-1.5 transition-colors"
                        >
                            <ExternalLink class="w-3.5 h-3.5 text-amber-400" />
                            <span>Scopus</span>
                        </a>

                        <a 
                            v-if="peneliti.orcid_id" 
                            :href="`https://orcid.org/${peneliti.orcid_id}`" 
                            target="_blank"
                            class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-medium text-slate-200 flex items-center gap-1.5 transition-colors"
                        >
                            <ExternalLink class="w-3.5 h-3.5 text-emerald-400" />
                            <span>ORCID</span>
                        </a>
                    </div>
                </div>

                <!-- Metrics Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 pt-8">
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-slate-400">Total Luaran</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ total_karya }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-emerald-400">Penelitian</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ peneliti.penelitians?.length || 0 }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-blue-400">Publikasi</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ peneliti.publikasis?.length || 0 }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-amber-400">PKM</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ peneliti.pkms?.length || 0 }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-violet-400">Buku</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ peneliti.bukus?.length || 0 }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 text-center">
                        <p class="text-[10px] uppercase font-semibold text-cyan-400">HKI / Paten</p>
                        <p class="text-xl font-extrabold text-white mt-0.5">{{ peneliti.hakis?.length || 0 }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Works Portfolio Explorer Tabs -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Tabs Menu -->
            <div class="flex items-center gap-2 border-b border-slate-200 overflow-x-auto pb-px mb-6 scrollbar-none">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
                        'px-4 py-3 text-xs sm:text-sm font-semibold border-b-2 shrink-0 transition-colors flex items-center gap-2',
                        activeTab === tab.id 
                            ? 'border-blue-600 text-blue-600 bg-blue-50/50' 
                            : 'border-transparent text-slate-500 hover:text-slate-800'
                    ]"
                >
                    <component :is="tab.icon" class="w-4 h-4" />
                    {{ tab.name }} ({{ tab.count }})
                </button>
            </div>

            <!-- Tab 1: Penelitian -->
            <div v-if="activeTab === 'penelitian'" class="space-y-4">
                <div v-if="peneliti.penelitians && peneliti.penelitians.length > 0" class="space-y-3">
                    <div v-for="item in peneliti.penelitians" :key="item.id" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 transition-all">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ item.pivot?.peran || 'Peneliti' }}
                                </span>
                                <span class="text-xs font-mono text-slate-500">{{ item.tahun }}</span>
                                <span v-if="item.nomor" class="text-xs text-slate-400 font-mono">• {{ item.nomor }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a v-if="item.tautan_tagihan" :href="item.tautan_tagihan" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                    <ExternalLink class="w-3.5 h-3.5" /> Buka Tagihan
                                </a>
                                <a v-if="item.file_path" :href="`/storage/${item.file_path}`" target="_blank" class="text-xs text-emerald-600 hover:underline flex items-center gap-1">
                                    <Download class="w-3.5 h-3.5" /> Unduh Laporan
                                </a>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ item.judul }}</h3>
                        <p class="text-xs text-slate-500">{{ item.skema_bantuan?.nama || 'Hibah Riset' }}</p>
                    </div>
                </div>
                <div v-else class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data penelitian tercatat.
                </div>
            </div>

            <!-- Tab 2: Publikasi -->
            <div v-if="activeTab === 'publikasi'" class="space-y-4">
                <div v-if="peneliti.publikasis && peneliti.publikasis.length > 0" class="space-y-3">
                    <div v-for="item in peneliti.publikasis" :key="item.id" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-blue-300 transition-all">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ item.kategori_indeks }}
                                </span>
                                <span class="text-xs font-mono text-slate-500">{{ item.tahun }}</span>
                                <span class="text-xs text-slate-400 capitalize">• {{ item.jenis_publikasi }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a v-if="item.tautan_doi" :href="item.tautan_doi" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                    <ExternalLink class="w-3.5 h-3.5" /> DOI / Sumber
                                </a>
                                <a v-if="item.file_path" :href="`/storage/${item.file_path}`" target="_blank" class="text-xs text-emerald-600 hover:underline flex items-center gap-1">
                                    <Download class="w-3.5 h-3.5" /> Unduh Dokumen
                                </a>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ item.judul }}</h3>
                        <p class="text-xs text-slate-500">{{ item.nama_jurnal_prosiding }}</p>
                    </div>
                </div>
                <div v-else class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data publikasi tercatat.
                </div>
            </div>

            <!-- Tab 3: PKM -->
            <div v-if="activeTab === 'pkm'" class="space-y-4">
                <div v-if="peneliti.pkms && peneliti.pkms.length > 0" class="space-y-3">
                    <div v-for="item in peneliti.pkms" :key="item.id" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-amber-300 transition-all">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ item.pivot?.peran || 'Pengabdi' }}
                                </span>
                                <span class="text-xs font-mono text-slate-500">{{ item.tahun }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a v-if="item.tautan_tagihan" :href="item.tautan_tagihan" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                    <ExternalLink class="w-3.5 h-3.5" /> Buka Tagihan
                                </a>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ item.judul }}</h3>
                        <p class="text-xs text-slate-500">{{ item.skema_bantuan?.nama || 'Program Pengabdian' }}</p>
                    </div>
                </div>
                <div v-else class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data pengabdian masyarakat tercatat.
                </div>
            </div>

            <!-- Tab 4: Buku -->
            <div v-if="activeTab === 'buku'" class="space-y-4">
                <div v-if="peneliti.bukus && peneliti.bukus.length > 0" class="space-y-3">
                    <div v-for="item in peneliti.bukus" :key="item.id" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-violet-300 transition-all">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-200">
                                    Buku
                                </span>
                                <span class="text-xs font-mono text-slate-500">{{ item.tahun }}</span>
                                <span v-if="item.isbn" class="text-xs text-slate-400 font-mono">• ISBN: {{ item.isbn }}</span>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ item.judul }}</h3>
                        <p class="text-xs text-slate-500">Penerbit: {{ item.nama_penerbit }}</p>
                    </div>
                </div>
                <div v-else class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data buku tercatat.
                </div>
            </div>

            <!-- Tab 5: HKI -->
            <div v-if="activeTab === 'haki'" class="space-y-4">
                <div v-if="peneliti.hakis && peneliti.hakis.length > 0" class="space-y-3">
                    <div v-for="item in peneliti.hakis" :key="item.id" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-cyan-300 transition-all">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                    {{ item.jenis_ciptaan }}
                                </span>
                                <span class="text-xs font-mono text-slate-500">{{ item.tahun }}</span>
                                <span v-if="item.nomor_hki" class="text-xs text-slate-400 font-mono">• No. {{ item.nomor_hki }}</span>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ item.judul_ciptaan }}</h3>
                        <p class="text-xs text-slate-500">Pemegang Hak Cipta: {{ item.pemegang_hak_cipta || 'Institusi' }}</p>
                    </div>
                </div>
                <div v-else class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data HKI tercatat.
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    ExternalLink, Download, FlaskConical, FileText, 
    HandHeart, BookOpen, Award 
} from 'lucide-vue-next';

const props = defineProps({
    peneliti: { type: Object, required: true },
    total_karya: { type: Number, default: 0 },
});

const activeTab = ref('penelitian');

const tabs = computed(() => [
    { id: 'penelitian', name: 'Penelitian', count: props.peneliti.penelitians?.length || 0, icon: FlaskConical },
    { id: 'publikasi', name: 'Publikasi', count: props.peneliti.publikasis?.length || 0, icon: FileText },
    { id: 'pkm', name: 'PKM', count: props.peneliti.pkms?.length || 0, icon: HandHeart },
    { id: 'buku', name: 'Buku', count: props.peneliti.bukus?.length || 0, icon: BookOpen },
    { id: 'haki', name: 'HKI & Paten', count: props.peneliti.hakis?.length || 0, icon: Award },
]);
</script>
