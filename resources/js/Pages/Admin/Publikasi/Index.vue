<template>
    <AdminLayout>
        <template #header>Modul Publikasi (Jurnal & Prosiding)</template>
        <Head title="Kelola Publikasi — Admin" />

        <div class="space-y-6">
            <!-- Filter & Search -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-72">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                        <input 
                            type="text" 
                            v-model="search" 
                            @input="handleSearch"
                            placeholder="Cari judul, nama jurnal, atau penulis..."
                            class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                    </div>

                    <select 
                        v-model="jenis" 
                        @change="handleSearch"
                        class="text-xs px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                        <option value="">Semua Jenis</option>
                        <option value="jurnal">Jurnal</option>
                        <option value="prosiding">Prosiding</option>
                    </select>
                </div>

                <button 
                    @click="openAddDrawer"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <Plus class="w-4 h-4" />
                    <span>Input Publikasi Baru</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Judul Publikasi</th>
                                <th class="py-3 px-4">Indeks & Wadah</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Penulis</th>
                                <th class="py-3 px-4">Sumber / DOI</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="item in publikasis.data" :key="item.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 max-w-sm">
                                    <p class="font-bold text-slate-900 line-clamp-2">{{ item.judul }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 mb-0.5">
                                        {{ item.kategori_indeks }}
                                    </span>
                                    <p class="text-[11px] text-slate-500 font-medium truncate max-w-xs">{{ item.nama_jurnal_prosiding }}</p>
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-slate-700">{{ item.tahun }}</td>
                                <td class="py-3 px-4 max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        <span 
                                            v-for="p in item.penuliss" 
                                            :key="p.id"
                                            class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px]"
                                        >
                                            {{ p.nama_lengkap }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a 
                                            v-if="item.tautan_doi" 
                                            :href="item.tautan_doi" 
                                            target="_blank"
                                            class="text-blue-600 hover:underline flex items-center gap-0.5"
                                            title="Buka DOI / Tautan Asli"
                                        >
                                            <ExternalLink class="w-3.5 h-3.5" /> DOI
                                        </a>
                                        <a 
                                            v-if="item.file_path" 
                                            :href="`/storage/${item.file_path}`" 
                                            target="_blank"
                                            class="text-emerald-600 hover:underline flex items-center gap-0.5"
                                            title="Unduh Dokumen"
                                        >
                                            <Download class="w-3.5 h-3.5" /> PDF
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button 
                                            @click="openEditDrawer(item)" 
                                            class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg"
                                        >
                                            <Edit class="w-3.5 h-3.5" />
                                        </button>
                                        <button 
                                            @click="deleteItem(item)" 
                                            class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="publikasis.data.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data publikasi yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="publikasis.links && publikasis.links.length > 3" class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ publikasis.from }} - {{ publikasis.to }} dari {{ publikasis.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link 
                            v-for="(link, i) in publikasis.links" 
                            :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            :class="[
                                'px-2.5 py-1 text-xs rounded-lg border transition-colors',
                                link.active ? 'bg-blue-600 text-white border-blue-600 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50',
                                !link.url && 'opacity-40 cursor-not-allowed'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Drawer Form -->
        <Drawer :show="drawerOpen" @close="drawerOpen = false">
            <template #title>{{ isEditing ? 'Edit Publikasi Ilmiah' : 'Input Publikasi Baru' }}</template>
            <template #subtitle>Pilih jenis Jurnal atau Prosiding beserta kategori akreditasi/indeksasi.</template>
            <template #content>
                <form @submit.prevent="submitForm" class="space-y-4 text-xs">
                    <!-- Jenis Publikasi -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Jenis Publikasi *</label>
                            <select 
                                v-model="form.jenis_publikasi" 
                                required
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white font-semibold"
                            >
                                <option value="jurnal">Artikel Jurnal</option>
                                <option value="prosiding">Makalah Prosiding</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tahun Terbit *</label>
                            <input 
                                type="number" 
                                v-model="form.tahun" 
                                required 
                                min="2000" 
                                :max="new Date().getFullYear() + 1"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <!-- Kategori / Tingkat Indeks -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Kategori / Tingkat Indeksasi *</label>
                        <select 
                            v-model="form.kategori_indeks" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                        >
                            <option value="">-- Pilih Kategori Indeks --</option>
                            <template v-if="form.jenis_publikasi === 'jurnal'">
                                <option v-for="cat in kategoriJurnal" :key="cat" :value="cat">{{ cat }}</option>
                            </template>
                            <template v-else>
                                <option v-for="cat in kategoriProsiding" :key="cat" :value="cat">{{ cat }}</option>
                            </template>
                        </select>
                    </div>

                    <!-- Judul Publikasi -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Judul Artikel / Makalah *</label>
                        <textarea 
                            v-model="form.judul" 
                            required 
                            rows="3"
                            placeholder="Tuliskan judul publikasi secara lengkap..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        ></textarea>
                    </div>

                    <!-- Nama Jurnal / Prosiding -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Jurnal / Seminar Prosiding *</label>
                        <input 
                            type="text" 
                            v-model="form.nama_jurnal_prosiding" 
                            required 
                            placeholder="Contoh: IEEE Access / Sains dan Teknologi"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                    </div>

                    <!-- Penulis Multi-select -->
                    <MultiSelectPeneliti 
                        v-model="form.penulis_ids"
                        :penelitis="penelitis"
                        label="Daftar Penulis (Penulis Pertama, Korespondensi, Anggota)"
                    />

                    <!-- DOI Link -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tautan DOI / Halaman Artikel (URL)</label>
                        <div class="relative">
                            <LinkIcon class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="url" 
                                v-model="form.tautan_doi" 
                                placeholder="https://doi.org/10.1109/..."
                                class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            />
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Unggah Dokumen Publikasi (PDF maks 10MB)</label>
                        <input 
                            type="file" 
                            accept="application/pdf"
                            @change="handleFileUpload"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                    </div>
                </form>
            </template>
            <template #footer>
                <button 
                    type="button" 
                    @click="drawerOpen = false" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200 transition-colors"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    @click="submitForm" 
                    :disabled="form.processing"
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-xs transition-colors disabled:opacity-50"
                >
                    {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Simpan Data') }}
                </button>
            </template>
        </Drawer>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Drawer from '@/Components/Drawer.vue';
import MultiSelectPeneliti from '@/Components/MultiSelectPeneliti.vue';
import { Search, Plus, Edit, Trash2, ExternalLink, Download, Link as LinkIcon } from 'lucide-vue-next';

const props = defineProps({
    publikasis: { type: Object, required: true },
    penelitis: { type: Array, default: () => [] },
    kategoriJurnal: { type: Array, default: () => [] },
    kategoriProsiding: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const jenis = ref(props.filters.jenis || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

const form = useForm({
    jenis_publikasi: 'jurnal',
    kategori_indeks: '',
    judul: '',
    tahun: new Date().getFullYear(),
    nama_jurnal_prosiding: '',
    tautan_doi: '',
    file: null,
    penulis_ids: [],
});

const handleSearch = () => {
    router.get('/admin/publikasi', { 
        search: search.value || undefined, 
        jenis: jenis.value || undefined 
    }, { preserveState: true, preserveScroll: true });
};

const handleFileUpload = (e) => {
    form.file = e.target.files[0] || null;
};

const openAddDrawer = () => {
    isEditing.value = false;
    currentId.value = null;
    form.reset();
    form.jenis_publikasi = 'jurnal';
    form.tahun = new Date().getFullYear();
    form.clearErrors();
    drawerOpen.value = true;
};

const openEditDrawer = (item) => {
    isEditing.value = true;
    currentId.value = item.id;
    form.jenis_publikasi = item.jenis_publikasi;
    form.kategori_indeks = item.kategori_indeks;
    form.judul = item.judul;
    form.tahun = item.tahun;
    form.nama_jurnal_prosiding = item.nama_jurnal_prosiding;
    form.tautan_doi = item.tautan_doi;
    form.file = null;
    form.penulis_ids = item.penuliss?.map(p => p.id) || [];
    form.clearErrors();
    drawerOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(`/admin/publikasi/${currentId.value}`, {
            _method: 'PUT',
            onSuccess: () => { drawerOpen.value = false; },
        });
    } else {
        form.post('/admin/publikasi', {
            onSuccess: () => { drawerOpen.value = false; form.reset(); },
        });
    }
};

const deleteItem = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus publikasi "${item.judul}"?`)) {
        router.delete(`/admin/publikasi/${item.id}`);
    }
};
</script>
