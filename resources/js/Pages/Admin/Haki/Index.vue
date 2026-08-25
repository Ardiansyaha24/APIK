<template>
    <AdminLayout>
        <template #header>Modul HKI & Hak Cipta</template>
        <Head title="Kelola HKI — Admin" />

        <div class="space-y-6">
            <!-- Header Actions & Search -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="relative w-full sm:w-72">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                    <input 
                        type="text" 
                        v-model="search" 
                        @input="handleSearch"
                        placeholder="Cari judul ciptaan, no HKI, pencipta..."
                        class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                    />
                </div>

                <button 
                    @click="openAddDrawer"
                    class="w-full sm:w-auto px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <Plus class="w-4 h-4" />
                    <span>Input HKI Baru</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Judul Ciptaan</th>
                                <th class="py-3 px-4">Jenis & No. HKI</th>
                                <th class="py-3 px-4">Tahun</th>
                                <th class="py-3 px-4">Pencipta</th>
                                <th class="py-3 px-4">Sertifikat / Bukti</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="item in hakis.data" :key="item.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 max-w-sm">
                                    <p class="font-bold text-slate-900 line-clamp-2">{{ item.judul_ciptaan }}</p>
                                    <p class="text-[10px] text-slate-400">Pemegang: {{ item.pemegang_hak_cipta || 'Institusi' }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <p class="font-medium text-cyan-700 truncate max-w-xs">{{ item.jenis_ciptaan }}</p>
                                    <p v-if="item.nomor_hki" class="text-[10px] text-slate-400 font-mono">No: {{ item.nomor_hki }}</p>
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-slate-700">{{ item.tahun }}</td>
                                <td class="py-3 px-4 max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        <span 
                                            v-for="p in item.penciptas" 
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
                            <tr v-if="hakis.data.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data HKI yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="hakis.links && hakis.links.length > 3" class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ hakis.from }} - {{ hakis.to }} dari {{ hakis.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link 
                            v-for="(link, i) in hakis.links" 
                            :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            :class="[
                                'px-2.5 py-1 text-xs rounded-lg border transition-colors',
                                link.active ? 'bg-cyan-600 text-white border-cyan-600 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50',
                                !link.url && 'opacity-40 cursor-not-allowed'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Drawer Form -->
        <Drawer :show="drawerOpen" @close="drawerOpen = false">
            <template #title>{{ isEditing ? 'Edit Data HKI / Hak Cipta' : 'Input HKI Baru' }}</template>
            <template #subtitle>Lengkapi formulir pencatatan hak cipta atau paten.</template>
            <template #content>
                <form @submit.prevent="submitForm" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Judul Ciptaan *</label>
                        <textarea 
                            v-model="form.judul_ciptaan" 
                            required 
                            rows="3"
                            placeholder="Tuliskan judul ciptaan/karya intelektual..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Jenis Ciptaan (UU 28/2014) *</label>
                        <select 
                            v-model="form.jenis_ciptaan" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none bg-white"
                        >
                            <option value="">-- Pilih Jenis Ciptaan --</option>
                            <option v-for="jenis in jenisCiptaanList" :key="jenis" :value="jenis">
                                {{ jenis }}
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor Registrasi / Pencatatan HKI</label>
                            <input 
                                type="text" 
                                v-model="form.nomor_hki" 
                                placeholder="EC002025..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none font-mono"
                            />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tahun Pencatatan *</label>
                            <input 
                                type="number" 
                                v-model="form.tahun" 
                                required 
                                min="2000" 
                                :max="new Date().getFullYear() + 1"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pemegang Hak Cipta</label>
                        <input 
                            type="text" 
                            v-model="form.pemegang_hak_cipta" 
                            placeholder="Contoh: LP2M / Universitas"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none"
                        />
                    </div>

                    <MultiSelectPeneliti 
                        v-model="form.pencipta_ids"
                        :penelitis="penelitis"
                        label="Daftar Pencipta Karya"
                    />

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tautan Sertifikat / Tagihan (URL)</label>
                        <input 
                            type="url" 
                            v-model="form.tautan_tagihan" 
                            placeholder="https://e-hakcipta.dgip.go.id/..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Unggah Sertifikat / Dokumen HKI (PDF maks 10MB)</label>
                        <input 
                            type="file" 
                            accept="application/pdf"
                            @change="handleFileUpload"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100"
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
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-cyan-600 hover:bg-cyan-500 text-white shadow-xs transition-colors disabled:opacity-50"
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
    hakis: { type: Object, required: true },
    penelitis: { type: Array, default: () => [] },
    jenisCiptaanList: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

const form = useForm({
    nomor_hki: '',
    pemegang_hak_cipta: '',
    jenis_ciptaan: '',
    judul_ciptaan: '',
    tahun: new Date().getFullYear(),
    tautan_tagihan: '',
    file: null,
    pencipta_ids: [],
});

const handleSearch = () => {
    router.get('/admin/haki', { search: search.value || undefined }, { preserveState: true, preserveScroll: true });
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
    form.nomor_hki = item.nomor_hki;
    form.pemegang_hak_cipta = item.pemegang_hak_cipta;
    form.jenis_ciptaan = item.jenis_ciptaan;
    form.judul_ciptaan = item.judul_ciptaan;
    form.tahun = item.tahun;
    form.tautan_tagihan = item.tautan_tagihan;
    form.file = null;
    form.pencipta_ids = item.penciptas?.map(p => p.id) || [];
    form.clearErrors();
    drawerOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(`/admin/haki/${currentId.value}`, {
            _method: 'PUT',
            onSuccess: () => { drawerOpen.value = false; },
        });
    } else {
        form.post('/admin/haki', {
            onSuccess: () => { drawerOpen.value = false; form.reset(); },
        });
    }
};

const deleteItem = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data HKI "${item.judul_ciptaan}"?`)) {
        router.delete(`/admin/haki/${item.id}`);
    }
};
</script>
