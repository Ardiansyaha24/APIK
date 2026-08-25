<template>
    <AdminLayout>
        <template #header>Modul Buku & Monograf</template>
        <Head title="Kelola Buku — Admin" />

        <div class="space-y-6">
            <!-- Header Actions & Search -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="relative w-full sm:w-72">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                    <input 
                        type="text" 
                        v-model="search" 
                        @input="handleSearch"
                        placeholder="Cari judul, ISBN, penerbit, penulis..."
                        class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                    />
                </div>

                <button 
                    @click="openAddDrawer"
                    class="w-full sm:w-auto px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <Plus class="w-4 h-4" />
                    <span>Input Buku Baru</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Judul Buku</th>
                                <th class="py-3 px-4">Penerbit & ISBN</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Penulis</th>
                                <th class="py-3 px-4">Berkas / Tautan</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="item in bukus.data" :key="item.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 max-w-sm">
                                    <p class="font-bold text-slate-900 line-clamp-2">{{ item.judul }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <p class="font-medium text-violet-700">{{ item.nama_penerbit }}</p>
                                    <p v-if="item.isbn" class="text-[10px] text-slate-400 font-mono">ISBN: {{ item.isbn }}</p>
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
                                            v-if="item.tautan" 
                                            :href="item.tautan" 
                                            target="_blank"
                                            class="text-blue-600 hover:underline flex items-center gap-0.5"
                                        >
                                            <ExternalLink class="w-3.5 h-3.5" /> Tautan
                                        </a>
                                        <a 
                                            v-if="item.file_path" 
                                            :href="`/storage/${item.file_path}`" 
                                            target="_blank"
                                            class="text-emerald-600 hover:underline flex items-center gap-0.5"
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
                            <tr v-if="bukus.data.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data buku yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="bukus.links && bukus.links.length > 3" class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ bukus.from }} - {{ bukus.to }} dari {{ bukus.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link 
                            v-for="(link, i) in bukus.links" 
                            :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            :class="[
                                'px-2.5 py-1 text-xs rounded-lg border transition-colors',
                                link.active ? 'bg-violet-600 text-white border-violet-600 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50',
                                !link.url && 'opacity-40 cursor-not-allowed'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Drawer Form -->
        <Drawer :show="drawerOpen" @close="drawerOpen = false">
            <template #title>{{ isEditing ? 'Edit Data Buku' : 'Input Buku Baru' }}</template>
            <template #subtitle>Lengkapi identitas buku ajar atau monograf.</template>
            <template #content>
                <form @submit.prevent="submitForm" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Judul Buku *</label>
                        <textarea 
                            v-model="form.judul" 
                            required 
                            rows="3"
                            placeholder="Tuliskan judul buku secara lengkap..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-violet-500 outline-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Penerbit *</label>
                        <input 
                            type="text" 
                            v-model="form.nama_penerbit" 
                            required 
                            placeholder="Contoh: Deepublish / Prenadamedia"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-violet-500 outline-none"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor ISBN</label>
                            <input 
                                type="text" 
                                v-model="form.isbn" 
                                placeholder="978-623-..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-violet-500 outline-none font-mono"
                            />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tahun Terbit *</label>
                            <input 
                                type="number" 
                                v-model="form.tahun" 
                                required 
                                min="2000" 
                                :max="new Date().getFullYear() + 1"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-violet-500 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <MultiSelectPeneliti 
                        v-model="form.penulis_ids"
                        :penelitis="penelitis"
                        label="Daftar Penulis Buku"
                    />

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tautan Web Buku / Katalog Penerbit (URL)</label>
                        <input 
                            type="url" 
                            v-model="form.tautan" 
                            placeholder="https://penerbit.com/..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-violet-500 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Unggah Cover / E-Book (PDF maks 20MB)</label>
                        <input 
                            type="file" 
                            accept="application/pdf"
                            @change="handleFileUpload"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
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
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-violet-600 hover:bg-violet-500 text-white shadow-xs transition-colors disabled:opacity-50"
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
import { Search, Plus, Edit, Trash2, ExternalLink, Download } from 'lucide-vue-next';

const props = defineProps({
    bukus: { type: Object, required: true },
    penelitis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

const form = useForm({
    nomor: '',
    nama_penerbit: '',
    judul: '',
    tahun: new Date().getFullYear(),
    isbn: '',
    tautan: '',
    file: null,
    penulis_ids: [],
});

const handleSearch = () => {
    router.get('/admin/buku', { search: search.value || undefined }, { preserveState: true, preserveScroll: true });
};

const handleFileUpload = (e) => {
    form.file = e.target.files[0] || null;
};

const openAddDrawer = () => {
    isEditing.value = false;
    currentId.value = null;
    form.reset();
    form.tahun = new Date().getFullYear();
    form.clearErrors();
    drawerOpen.value = true;
};

const openEditDrawer = (item) => {
    isEditing.value = true;
    currentId.value = item.id;
    form.nomor = item.nomor;
    form.nama_penerbit = item.nama_penerbit;
    form.judul = item.judul;
    form.tahun = item.tahun;
    form.isbn = item.isbn;
    form.tautan = item.tautan;
    form.file = null;
    form.penulis_ids = item.penuliss?.map(p => p.id) || [];
    form.clearErrors();
    drawerOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(`/admin/buku/${currentId.value}`, {
            _method: 'PUT',
            onSuccess: () => { drawerOpen.value = false; },
        });
    } else {
        form.post('/admin/buku', {
            onSuccess: () => { drawerOpen.value = false; form.reset(); },
        });
    }
};

const deleteItem = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data buku "${item.judul}"?`)) {
        router.delete(`/admin/buku/${item.id}`);
    }
};
</script>
