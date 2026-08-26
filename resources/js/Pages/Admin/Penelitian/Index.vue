<template>
    <AdminLayout>
        <template #header>Modul Penelitian</template>
        <Head title="Kelola Penelitian — Admin" />

        <div class="space-y-6">
            <!-- Header Actions & Filter -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-72">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                        <input 
                            type="text" 
                            v-model="search" 
                            @input="handleSearch"
                            placeholder="Cari judul, nomor SK, atau peneliti..."
                            class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                    </div>
                </div>

                <button 
                    @click="openAddDrawer"
                    class="w-full sm:w-auto px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <Plus class="w-4 h-4" />
                    <span>Input Penelitian Baru</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Judul Penelitian</th>
                                <th class="py-3 px-4">Skema & Nomor</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Peneliti</th>
                                <th class="py-3 px-4">Bukti / Berkas</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="item in penelitians.data" :key="item.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 max-w-sm">
                                    <p class="font-bold text-slate-900 line-clamp-2">{{ item.judul }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <p class="font-medium text-emerald-700">{{ item.skema_bantuan?.nama || 'Hibah Riset' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ item.nomor || '-' }}</p>
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-slate-700">{{ item.tahun }}</td>
                                <td class="py-3 px-4 max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        <span 
                                            v-for="p in item.penelitis" 
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
                                            v-if="item.tautan_tagihan" 
                                            :href="item.tautan_tagihan" 
                                            target="_blank"
                                            class="text-blue-600 hover:underline flex items-center gap-0.5"
                                            title="Tautan Tagihan"
                                        >
                                            <ExternalLink class="w-3.5 h-3.5" /> Tagihan
                                        </a>
                                        <a 
                                            v-if="item.file_path" 
                                            :href="`/storage/${item.file_path}`" 
                                            target="_blank"
                                            class="text-emerald-600 hover:underline flex items-center gap-0.5"
                                            title="Unduh Berkas"
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
                                            title="Edit"
                                        >
                                            <Edit class="w-3.5 h-3.5" />
                                        </button>
                                        <button 
                                            @click="deleteItem(item)" 
                                            class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg"
                                            title="Hapus"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="penelitians.data.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data penelitian yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="penelitians.links && penelitians.links.length > 3" class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ penelitians.from }} - {{ penelitians.to }} dari {{ penelitians.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link 
                            v-for="(link, i) in penelitians.links" 
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
            <template #title>{{ isEditing ? 'Edit Data Penelitian' : 'Input Penelitian Baru' }}</template>
            <template #subtitle>Lengkapi informasi nomor SK, skema bantuan, peneliti, dan berkas bukti.</template>
            <template #content>
                <form @submit.prevent="submitForm" class="space-y-4 text-xs">
                    <!-- Judul -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Judul Penelitian *</label>
                        <textarea 
                            v-model="form.judul" 
                            required 
                            rows="3"
                            placeholder="Tuliskan judul penelitian secara lengkap..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        ></textarea>
                        <p v-if="form.errors.judul" class="text-rose-500 mt-1">{{ form.errors.judul }}</p>
                    </div>

                    <!-- Nomor & Tahun -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor Registrasi / SK</label>
                            <input 
                                type="text" 
                                v-model="form.nomor" 
                                placeholder="SK-LP2M/2026/001"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                            />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tahun Pelaksanaan *</label>
                            <input 
                                type="number" 
                                v-model="form.tahun" 
                                required 
                                min="2000" 
                                :max="new Date().getFullYear() + 1"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                            />
                            <p v-if="form.errors.tahun" class="text-rose-500 mt-1">{{ form.errors.tahun }}</p>
                        </div>
                    </div>

                    <!-- Skema Bantuan -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-semibold text-slate-700">Nama Skema / Program Bantuan</label>
                            <button 
                                type="button" 
                                @click="isCustomSkema = !isCustomSkema; if (isCustomSkema) { form.skema_bantuan_id = null; } else { form.skema_bantuan_nama = ''; }"
                                class="text-[11px] text-blue-600 hover:underline font-semibold cursor-pointer"
                            >
                                {{ isCustomSkema ? '← Pilih dari Daftar' : '+ Ketik Skema Baru' }}
                            </button>
                        </div>
                        
                        <div v-if="!isCustomSkema">
                            <select 
                                v-model="form.skema_bantuan_id" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white text-xs"
                            >
                                <option :value="null">-- Pilih Skema Bantuan --</option>
                                <option v-for="skema in skemas" :key="skema.id" :value="skema.id">
                                    {{ skema.nama }}
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            <input 
                                type="text"
                                list="skema-datalist-penelitian"
                                v-model="form.skema_bantuan_nama"
                                placeholder="Ketikkan nama skema bantuan baru / custom..."
                                class="w-full px-3 py-2 rounded-xl border border-blue-400 focus:ring-2 focus:ring-blue-500 outline-none text-xs bg-blue-50/20"
                            />
                            <datalist id="skema-datalist-penelitian">
                                <option v-for="skema in skemas" :key="skema.id" :value="skema.nama"></option>
                            </datalist>
                        </div>
                    </div>

                    <!-- Peneliti Multi-select -->
                    <MultiSelectPeneliti 
                        v-model="form.peneliti_ids"
                        :penelitis="penelitis"
                        label="Daftar Peneliti (Ketua & Anggota)"
                    />
                    <p v-if="form.errors.peneliti_ids" class="text-rose-500 mt-1">{{ form.errors.peneliti_ids }}</p>

                    <!-- Tautan Tagihan -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tautan Bukti Tagihan / Pencairan Dana (URL)</label>
                        <div class="relative">
                            <LinkIcon class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="url" 
                                v-model="form.tautan_tagihan" 
                                placeholder="https://drive.google.com/..."
                                class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            />
                        </div>
                    </div>

                    <!-- Upload PDF -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Unggah Laporan Hasil Penelitian (PDF maks 10MB)</label>
                        <input 
                            type="file" 
                            accept="application/pdf"
                            @change="handleFileUpload"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p v-if="form.errors.file" class="text-rose-500 mt-1">{{ form.errors.file }}</p>
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
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-xs transition-colors disabled:opacity-50"
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
    penelitians: { type: Object, required: true },
    skemas: { type: Array, default: () => [] },
    penelitis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);
const isCustomSkema = ref(false);

const form = useForm({
    nomor: '',
    skema_bantuan_id: null,
    skema_bantuan_nama: '',
    judul: '',
    tahun: new Date().getFullYear(),
    tautan_tagihan: '',
    file: null,
    peneliti_ids: [],
});

const handleSearch = () => {
    router.get('/admin/penelitian', { search: search.value || undefined }, { preserveState: true, preserveScroll: true });
};

const handleFileUpload = (e) => {
    form.file = e.target.files[0] || null;
};

const openAddDrawer = () => {
    isEditing.value = false;
    currentId.value = null;
    isCustomSkema.value = false;
    form.reset();
    form.tahun = new Date().getFullYear();
    form.clearErrors();
    drawerOpen.value = true;
};

const openEditDrawer = (item) => {
    isEditing.value = true;
    currentId.value = item.id;
    isCustomSkema.value = false;
    form.nomor = item.nomor;
    form.skema_bantuan_id = item.skema_bantuan_id;
    form.skema_bantuan_nama = '';
    form.judul = item.judul;
    form.tahun = item.tahun;
    form.tautan_tagihan = item.tautan_tagihan;
    form.file = null;
    form.peneliti_ids = item.penelitis?.map(p => p.id) || [];
    form.clearErrors();
    drawerOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(`/admin/penelitian/${currentId.value}`, {
            _method: 'PUT',
            onSuccess: () => { drawerOpen.value = false; },
        });
    } else {
        form.post('/admin/penelitian', {
            onSuccess: () => { drawerOpen.value = false; form.reset(); },
        });
    }
};

const deleteItem = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus penelitian "${item.judul}"?`)) {
        router.delete(`/admin/penelitian/${item.id}`);
    }
};
</script>
