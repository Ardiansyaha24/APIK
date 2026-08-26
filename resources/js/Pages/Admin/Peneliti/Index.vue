<template>
    <AdminLayout>
        <template #header>Pengelolaan Data Peneliti</template>
        <Head title="Kelola Peneliti — Admin" />

        <div class="space-y-6">
            <!-- Header Action & Filters -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div class="flex-1 flex items-center gap-3">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" />
                        <input 
                            type="text" 
                            v-model="search" 
                            @keyup.enter="handleSearch"
                            placeholder="Cari nama, NIDN, email, atau bidang keahlian..." 
                            class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 text-xs transition-all outline-none"
                        />
                    </div>
                    <button 
                        @click="handleSearch"
                        class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition-colors cursor-pointer"
                    >
                        Cari
                    </button>
                </div>

                <button 
                    @click="openAddDrawer" 
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
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
                                <th class="py-3 px-4">Nama & Email</th>
                                <th class="py-3 px-4">NIDN</th>
                                <th class="py-3 px-4">Bidang Keahlian</th>
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
                                    <p class="truncate max-w-xs">{{ p.bidang_keahlian || '-' }}</p>
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
                                            class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer"
                                            title="Edit"
                                        >
                                            <Edit class="w-3.5 h-3.5" />
                                        </button>
                                        <button 
                                            @click="deletePeneliti(p)" 
                                            class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
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
            <template #subtitle>Lengkapi identitas, bidang keahlian, dan ID profil akademik peneliti.</template>
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

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat Email</label>
                            <input 
                                type="email" 
                                v-model="form.email" 
                                placeholder="dosen@iain-manado.ac.id"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                            <input 
                                type="text" 
                                v-model="form.no_hp" 
                                placeholder="081234567890"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bidang Keahlian / Kepakaran</label>
                        <textarea 
                            v-model="form.bidang_keahlian" 
                            rows="2"
                            placeholder="Contoh: Pendidikan Islam, Hukum Keluarga, Ekonomi Syariah, dll."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        ></textarea>
                    </div>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                        <p class="font-bold text-slate-700">Integrasi ID Profil Akademik</p>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-medium text-slate-600 mb-0.5">SINTA ID</label>
                                <input type="text" v-model="form.sinta_id" placeholder="6012345" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-mono text-[11px]" />
                            </div>
                            <div>
                                <label class="block font-medium text-slate-600 mb-0.5">Scopus Author ID</label>
                                <input type="text" v-model="form.scopus_id" placeholder="572012345" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-mono text-[11px]" />
                            </div>
                            <div>
                                <label class="block font-medium text-slate-600 mb-0.5">Google Scholar ID</label>
                                <input type="text" v-model="form.gscholar_id" placeholder="AbCdEfGAAAAJ" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-mono text-[11px]" />
                            </div>
                            <div>
                                <label class="block font-medium text-slate-600 mb-0.5">ORCID ID</label>
                                <input type="text" v-model="form.orcid_id" placeholder="0000-0002-1825-0097" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white font-mono text-[11px]" />
                            </div>
                        </div>
                    </div>
                </form>
            </template>
            <template #footer>
                <button 
                    type="button" 
                    @click="drawerOpen = false" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    @click="submitForm" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-xs cursor-pointer"
                >
                    {{ isEditing ? 'Simpan Perubahan' : 'Daftarkan Peneliti' }}
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
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const drawerOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);

const form = useForm({
    nama_lengkap: '',
    nidn: '',
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

const openEditDrawer = (item) => {
    isEditing.value = true;
    currentId.value = item.id;
    form.nama_lengkap = item.nama_lengkap;
    form.nidn = item.nidn || '';
    form.email = item.email || '';
    form.no_hp = item.no_hp || '';
    form.bidang_keahlian = item.bidang_keahlian || '';
    form.sinta_id = item.sinta_id || '';
    form.scopus_id = item.scopus_id || '';
    form.gscholar_id = item.gscholar_id || '';
    form.orcid_id = item.orcid_id || '';
    form.status = item.status;
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

const deletePeneliti = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data peneliti "${item.nama_lengkap}"?`)) {
        router.delete(`/admin/peneliti/${item.id}`);
    }
};
</script>
