<template>
    <AdminLayout>
        <template #header>Master Data Sistem (Fakultas, Prodi & Skema)</template>
        <Head title="Master Data — Admin" />

        <div class="space-y-8">
            <!-- 1. Fakultas & Program Studi -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Fakultas & Program Studi</h2>
                        <p class="text-xs text-slate-500">Struktur unit akademik penaung dosen dan peneliti</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="openFakultasModal()"
                            class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center gap-1.5"
                        >
                            <Plus class="w-3.5 h-3.5" />
                            + Fakultas
                        </button>
                        <button 
                            @click="openProdiModal()"
                            class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center gap-1.5"
                        >
                            <Plus class="w-3.5 h-3.5" />
                            + Program Studi
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div 
                        v-for="fak in fakultasList" 
                        :key="fak.id"
                        class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">{{ fak.nama }}</h3>
                                <p class="text-[10px] text-slate-400 font-mono">Kode: {{ fak.kode || '-' }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button @click="openFakultasModal(fak)" class="p-1 text-slate-400 hover:text-blue-600">
                                    <Edit class="w-3.5 h-3.5" />
                                </button>
                                <button @click="deleteFakultas(fak)" class="p-1 text-slate-400 hover:text-rose-600">
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>

                        <!-- Prodi Sub-list -->
                        <div class="space-y-1.5">
                            <div 
                                v-for="prodi in fak.prodis" 
                                :key="prodi.id"
                                class="px-3 py-1.5 rounded-lg bg-white border border-slate-200/70 text-xs flex items-center justify-between"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.2 rounded bg-blue-50 text-blue-700 font-bold text-[10px] font-mono">
                                        {{ prodi.jenjang }}
                                    </span>
                                    <span class="font-medium text-slate-700">{{ prodi.nama }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="openProdiModal(prodi)" class="p-1 text-slate-400 hover:text-blue-600">
                                        <Edit class="w-3 h-3" />
                                    </button>
                                    <button @click="deleteProdi(prodi)" class="p-1 text-slate-400 hover:text-rose-600">
                                        <Trash2 class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                            <p v-if="fak.prodis.length === 0" class="text-[11px] text-slate-400 italic">Belum ada prodi terdaftar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Skema Bantuan Penelitian & PKM -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Master Skema Bantuan Hibah</h2>
                        <p class="text-xs text-slate-500">Klasifikasi skema dana bantuan Penelitian dan PKM internal</p>
                    </div>

                    <button 
                        @click="openSkemaModal()"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center gap-1.5"
                    >
                        <Plus class="w-3.5 h-3.5" />
                        + Skema Bantuan
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-y border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Nama Skema</th>
                                <th class="py-3 px-4">Jenis Peruntukan</th>
                                <th class="py-3 px-4">Keterangan</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="skema in skemaList" :key="skema.id" class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 font-bold text-slate-800">{{ skema.nama }}</td>
                                <td class="py-3 px-4">
                                    <span 
                                        :class="[
                                            'px-2 py-0.5 rounded text-[10px] font-semibold uppercase',
                                            skema.jenis === 'penelitian' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                            skema.jenis === 'pkm' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200'
                                        ]"
                                    >
                                        {{ skema.jenis }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500 max-w-sm">{{ skema.keterangan || '-' }}</td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button @click="openSkemaModal(skema)" class="p-1.5 text-slate-500 hover:text-blue-600">
                                            <Edit class="w-3.5 h-3.5" />
                                        </button>
                                        <button @click="deleteSkema(skema)" class="p-1.5 text-slate-500 hover:text-rose-600">
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Form Fakultas -->
        <Modal :show="fakultasModalOpen" @close="fakultasModalOpen = false" maxWidth="sm">
            <template #title>{{ fakultasEditing ? 'Edit Fakultas' : 'Tambah Fakultas Baru' }}</template>
            <template #content>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Fakultas *</label>
                        <input type="text" v-model="fakultasForm.nama" placeholder="Contoh: Fakultas Sains dan Teknologi" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Kode Singkatan</label>
                        <input type="text" v-model="fakultasForm.kode" placeholder="FST" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 uppercase font-mono" />
                    </div>
                </div>
            </template>
            <template #footer>
                <button type="button" @click="fakultasModalOpen = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                <button type="button" @click="submitFakultas" class="px-3.5 py-1.5 text-xs bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500">Simpan</button>
            </template>
        </Modal>

        <!-- Modal Form Prodi -->
        <Modal :show="prodiModalOpen" @close="prodiModalOpen = false" maxWidth="sm">
            <template #title>{{ prodiEditing ? 'Edit Program Studi' : 'Tambah Program Studi Baru' }}</template>
            <template #content>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Fakultas *</label>
                        <select v-model="prodiForm.fakultas_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white">
                            <option v-for="f in fakultasList" :key="f.id" :value="f.id">{{ f.nama }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Program Studi *</label>
                        <input type="text" v-model="prodiForm.nama" placeholder="Contoh: Teknik Informatika" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Jenjang *</label>
                            <select v-model="prodiForm.jenjang" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white">
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                                <option value="Profesi">Profesi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kode</label>
                            <input type="text" v-model="prodiForm.kode" placeholder="TI" class="w-full px-3 py-2 rounded-xl border border-slate-300 uppercase font-mono" />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button type="button" @click="prodiModalOpen = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                <button type="button" @click="submitProdi" class="px-3.5 py-1.5 text-xs bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500">Simpan</button>
            </template>
        </Modal>

        <!-- Modal Form Skema Bantuan -->
        <Modal :show="skemaModalOpen" @close="skemaModalOpen = false" maxWidth="md">
            <template #title>{{ skemaEditing ? 'Edit Skema Bantuan' : 'Tambah Skema Bantuan Baru' }}</template>
            <template #content>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Skema / Program *</label>
                        <input type="text" v-model="skemaForm.nama" placeholder="Contoh: Bantuan Penelitian Terapan" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Jenis Peruntukan *</label>
                        <select v-model="skemaForm.jenis" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white">
                            <option value="penelitian">Penelitian Saja</option>
                            <option value="pkm">Pengabdian (PKM) Saja</option>
                            <option value="keduanya">Keduanya (Penelitian & PKM)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Keterangan Tambahan</label>
                        <textarea v-model="skemaForm.keterangan" rows="2" placeholder="Deskripsi ringkas skema..." class="w-full px-3 py-2 rounded-xl border border-slate-300"></textarea>
                    </div>
                </div>
            </template>
            <template #footer>
                <button type="button" @click="skemaModalOpen = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                <button type="button" @click="submitSkema" class="px-3.5 py-1.5 text-xs bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500">Simpan</button>
            </template>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Plus, Edit, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    fakultasList: { type: Array, default: () => [] },
    skemaList: { type: Array, default: () => [] },
});

// Fakultas Modal State
const fakultasModalOpen = ref(false);
const fakultasEditing = ref(false);
const fakultasId = ref(null);
const fakultasForm = useForm({ nama: '', kode: '' });

const openFakultasModal = (item = null) => {
    if (item) {
        fakultasEditing.value = true;
        fakultasId.value = item.id;
        fakultasForm.nama = item.nama;
        fakultasForm.kode = item.kode;
    } else {
        fakultasEditing.value = false;
        fakultasId.value = null;
        fakultasForm.reset();
    }
    fakultasModalOpen.value = true;
};

const submitFakultas = () => {
    if (fakultasEditing.value) {
        fakultasForm.put(`/admin/master/fakultas/${fakultasId.value}`, {
            onSuccess: () => { fakultasModalOpen.value = false; },
        });
    } else {
        fakultasForm.post('/admin/master/fakultas', {
            onSuccess: () => { fakultasModalOpen.value = false; fakultasForm.reset(); },
        });
    }
};

const deleteFakultas = (item) => {
    if (confirm(`Hapus fakultas "${item.nama}" beserta seluruh program studinya?`)) {
        router.delete(`/admin/master/fakultas/${item.id}`);
    }
};

// Prodi Modal State
const prodiModalOpen = ref(false);
const prodiEditing = ref(false);
const prodiId = ref(null);
const prodiForm = useForm({ fakultas_id: props.fakultasList[0]?.id || null, nama: '', jenjang: 'S1', kode: '' });

const openProdiModal = (item = null) => {
    if (item) {
        prodiEditing.value = true;
        prodiId.value = item.id;
        prodiForm.fakultas_id = item.fakultas_id;
        prodiForm.nama = item.nama;
        prodiForm.jenjang = item.jenjang;
        prodiForm.kode = item.kode;
    } else {
        prodiEditing.value = false;
        prodiId.value = null;
        prodiForm.reset();
        prodiForm.fakultas_id = props.fakultasList[0]?.id || null;
    }
    prodiModalOpen.value = true;
};

const submitProdi = () => {
    if (prodiEditing.value) {
        prodiForm.put(`/admin/master/prodi/${prodiId.value}`, {
            onSuccess: () => { prodiModalOpen.value = false; },
        });
    } else {
        prodiForm.post('/admin/master/prodi', {
            onSuccess: () => { prodiModalOpen.value = false; prodiForm.reset(); },
        });
    }
};

const deleteProdi = (item) => {
    if (confirm(`Hapus program studi "${item.nama}"?`)) {
        router.delete(`/admin/master/prodi/${item.id}`);
    }
};

// Skema Modal State
const skemaModalOpen = ref(false);
const skemaEditing = ref(false);
const skemaId = ref(null);
const skemaForm = useForm({ nama: '', jenis: 'penelitian', keterangan: '' });

const openSkemaModal = (item = null) => {
    if (item) {
        skemaEditing.value = true;
        skemaId.value = item.id;
        skemaForm.nama = item.nama;
        skemaForm.jenis = item.jenis;
        skemaForm.keterangan = item.keterangan;
    } else {
        skemaEditing.value = false;
        skemaId.value = null;
        skemaForm.reset();
    }
    skemaModalOpen.value = true;
};

const submitSkema = () => {
    if (skemaEditing.value) {
        skemaForm.put(`/admin/master/skema/${skemaId.value}`, {
            onSuccess: () => { skemaModalOpen.value = false; },
        });
    } else {
        skemaForm.post('/admin/master/skema', {
            onSuccess: () => { skemaModalOpen.value = false; skemaForm.reset(); },
        });
    }
};

const deleteSkema = (item) => {
    if (confirm(`Hapus skema bantuan "${item.nama}"?`)) {
        router.delete(`/admin/master/skema/${item.id}`);
    }
};
</script>
