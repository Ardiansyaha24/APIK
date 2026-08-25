<template>
    <AdminLayout>
        <template #header>Master Data Peneliti & Dosen</template>
        <Head title="Master Peneliti — Admin" />

        <div class="space-y-6">
            <!-- Header Actions & Search -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-72">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                        <input 
                            type="text" 
                            v-model="search" 
                            @input="handleSearch"
                            placeholder="Cari nama atau NIDN..."
                            class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                    </div>
                </div>

                <button 
                    @click="openAddDrawer"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <Plus class="w-4 h-4" />
                    <span>Tambah Peneliti</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Nama Lengkap</th>
                                <th class="py-3 px-4">NIDN / NIP</th>
                                <th class="py-3 px-4">Program Studi</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Total Karya</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="p in penelitis.data" :key="p.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900">{{ p.nama_lengkap }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ p.email || 'Email belum terisi' }}</p>
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-700">{{ p.nidn || '-' }}</td>
                                <td class="py-3 px-4 text-slate-600">
                                    <p>{{ p.prodi?.nama || '-' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ p.prodi?.fakultas?.kode }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <span 
                                        :class="[
                                            'px-2 py-0.5 rounded-full text-[10px] font-semibold',
                                            p.status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600'
                                        ]"
                                    >
                                        {{ p.status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-blue-600">
                                    {{ p.penelitians_count + p.bukus_count + p.pkms_count + p.hakis_count + p.publikasis_count }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button 
                                            @click="openEditDrawer(p)" 
                                            class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit"
                                        >
                                            <Edit class="w-3.5 h-3.5" />
                                        </button>
                                        <button 
                                            @click="deletePeneliti(p)" 
                                            class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="penelitis.data.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data peneliti yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="penelitis.links && penelitis.links.length > 3" class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ penelitis.from }} - {{ penelitis.to }} dari {{ penelitis.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link 
                            v-for="(link, i) in penelitis.links" 
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

        <!-- Drawer Form (Slide-over) -->
        <Drawer :show="drawerOpen" @close="drawerOpen = false">
            <template #title>{{ isEditing ? 'Edit Data Peneliti' : 'Tambah Peneliti Baru' }}</template>
            <template #subtitle>Pastikan NIDN dan data afiliasi prodi terisi dengan benar.</template>
            <template #content>
                <form @submit.prevent="submitForm" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                        <input 
                            type="text" 
                            v-model="form.nama_lengkap" 
                            required 
                            placeholder="Contoh: Prof. Dr. Faisal, M.T."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        />
                        <p v-if="form.errors.nama_lengkap" class="text-rose-500 mt-1">{{ form.errors.nama_lengkap }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">NIDN / NIP</label>
                            <input 
                                type="text" 
                                v-model="form.nidn" 
                                placeholder="Contoh: 2015088201"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                            />
                            <p v-if="form.errors.nidn" class="text-rose-500 mt-1">{{ form.errors.nidn }}</p>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status Keaktifan *</label>
                            <select 
                                v-model="form.status" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                            >
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                                <option value="purna_tugas">Purna Tugas</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Program Studi Afiliasi</label>
                        <select 
                            v-model="form.prodi_id" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                        >
                            <option :value="null">-- Pilih Program Studi --</option>
                            <option v-for="prodi in prodis" :key="prodi.id" :value="prodi.id">
                                {{ prodi.nama }} ({{ prodi.fakultas?.nama }})
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat Email</label>
                            <input 
                                type="email" 
                                v-model="form.email" 
                                placeholder="dosen@institusi.ac.id"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                            <input 
                                type="text" 
                                v-model="form.no_hp" 
                                placeholder="081234567890"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bidang Keahlian / Fokus Riset</label>
                        <textarea 
                            v-model="form.bidang_keahlian" 
                            rows="2"
                            placeholder="Contoh: Kecerdasan Buatan, Image Processing, Data Science"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        ></textarea>
                    </div>

                    <!-- External IDs -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Identitas Indeksasi Eksternal</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] text-slate-500">SINTA ID</label>
                                <input type="text" v-model="form.sinta_id" placeholder="6012345" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white" />
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500">Scopus Author ID</label>
                                <input type="text" v-model="form.scopus_id" placeholder="572019..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white" />
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500">Google Scholar ID</label>
                                <input type="text" v-model="form.gscholar_id" placeholder="abCD123..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white" />
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500">ORCID ID</label>
                                <input type="text" v-model="form.orcid_id" placeholder="0000-0002-..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white" />
                            </div>
                        </div>
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
                    {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Tambah Peneliti') }}
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
import { Search, Plus, Edit, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    penelitis: { type: Object, required: true },
    prodis: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

const form = useForm({
    nama_lengkap: '',
    nidn: '',
    prodi_id: null,
    email: '',
    no_hp: '',
    bidang_keahlian: '',
    sinta_id: '',
    scopus_id: '',
    gscholar_id: '',
    orcid_id: '',
    status: 'aktif',
});

const handleSearch = () => {
    router.get('/admin/peneliti', { search: search.value || undefined }, { preserveState: true, preserveScroll: true });
};

const openAddDrawer = () => {
    isEditing.value = false;
    currentId.value = null;
    form.reset();
    form.clearErrors();
    drawerOpen.value = true;
};

const openEditDrawer = (peneliti) => {
    isEditing.value = true;
    currentId.value = peneliti.id;
    form.nama_lengkap = peneliti.nama_lengkap;
    form.nidn = peneliti.nidn;
    form.prodi_id = peneliti.prodi_id;
    form.email = peneliti.email;
    form.no_hp = peneliti.no_hp;
    form.bidang_keahlian = peneliti.bidang_keahlian;
    form.sinta_id = peneliti.sinta_id;
    form.scopus_id = peneliti.scopus_id;
    form.gscholar_id = peneliti.gscholar_id;
    form.orcid_id = peneliti.orcid_id;
    form.status = peneliti.status;
    form.clearErrors();
    drawerOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/admin/peneliti/${currentId.value}`, {
            onSuccess: () => { drawerOpen.value = false; },
        });
    } else {
        form.post('/admin/peneliti', {
            onSuccess: () => { drawerOpen.value = false; form.reset(); },
        });
    }
};

const deletePeneliti = (peneliti) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data peneliti "${peneliti.nama_lengkap}"?`)) {
        router.delete(`/admin/peneliti/${peneliti.id}`);
    }
};
</script>
