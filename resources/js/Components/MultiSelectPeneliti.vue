<template>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-semibold text-slate-700">
                {{ label }} <span v-if="required" class="text-rose-500">*</span>
            </label>
            <button 
                type="button" 
                @click="openQuickAddModal = true" 
                class="text-[11px] text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1 hover:underline"
            >
                <Plus class="w-3 h-3" />
                + Peneliti Baru
            </button>
        </div>

        <!-- Search / Select dropdown -->
        <div class="relative">
            <input 
                type="text" 
                v-model="searchQuery" 
                @focus="dropdownOpen = true"
                placeholder="Ketik nama atau NIDN untuk menambah..."
                class="w-full text-xs px-3 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
            />

            <!-- Dropdown Options -->
            <div 
                v-if="dropdownOpen && filteredPenelitis.length > 0" 
                class="absolute z-30 left-0 right-0 mt-1 max-h-48 overflow-y-auto bg-white border border-slate-200 rounded-lg shadow-lg py-1 divide-y divide-slate-100"
            >
                <div 
                    v-for="item in filteredPenelitis" 
                    :key="item.id" 
                    @click="selectPeneliti(item)"
                    class="px-3 py-2 text-xs hover:bg-blue-50 cursor-pointer flex items-center justify-between"
                >
                    <div>
                        <p class="font-medium text-slate-800">{{ item.nama_lengkap }}</p>
                        <p class="text-[10px] text-slate-500 font-mono">NIDN: {{ item.nidn || '-' }}</p>
                    </div>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">+ Tambah</span>
                </div>
            </div>
        </div>

        <!-- Selected Chips List (Ordered) -->
        <div v-if="selectedList.length > 0" class="space-y-1.5 pt-1">
            <p class="text-[11px] text-slate-500 font-medium">Kontributor Terpilih (Urutan ke-1 = Ketua/Penulis Pertama):</p>
            <div class="space-y-1">
                <div 
                    v-for="(item, idx) in selectedList" 
                    :key="item.id"
                    class="flex items-center justify-between px-3 py-1.5 rounded-lg bg-slate-100 border border-slate-200 text-xs"
                >
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold">
                            {{ idx + 1 }}
                        </span>
                        <div>
                            <span class="font-medium text-slate-800">{{ item.nama_lengkap }}</span>
                            <span class="text-[10px] text-slate-500 ml-2">({{ idx === 0 ? 'Utama / Ketua' : 'Anggota' }})</span>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="removePeneliti(item.id)" 
                        class="text-slate-400 hover:text-rose-600 p-1"
                    >
                        <X class="w-3.5 h-3.5" />
                    </button>
                </div>
            </div>
        </div>
        <p v-else class="text-[11px] text-amber-600 italic">Belum ada peneliti/kontributor yang dipilih.</p>

        <!-- Quick Add Peneliti Modal -->
        <Modal :show="openQuickAddModal" @close="openQuickAddModal = false" maxWidth="sm">
            <template #title>Tambah Cepat Peneliti</template>
            <template #content>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                        <input 
                            type="text" 
                            v-model="quickForm.nama_lengkap" 
                            placeholder="Contoh: Dr. Ahmad Dahlan, M.Kom."
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">NIDN / NIP</label>
                        <input 
                            type="text" 
                            v-model="quickForm.nidn" 
                            placeholder="Contoh: 2018098001"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>
            </template>
            <template #footer>
                <button 
                    type="button" 
                    @click="openQuickAddModal = false" 
                    class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    @click="submitQuickAdd" 
                    :disabled="quickLoading || !quickForm.nama_lengkap"
                    class="px-3.5 py-1.5 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-500 font-semibold disabled:opacity-50"
                >
                    {{ quickLoading ? 'Menyimpan...' : 'Simpan & Pilih' }}
                </button>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Plus, X } from 'lucide-vue-next';
import Modal from './Modal.vue';

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    penelitis: { type: Array, default: () => [] },
    label: { type: String, default: 'Peneliti / Kontributor' },
    required: { type: Boolean, default: true },
});

const emit = defineEmits(['update:modelValue']);

const searchQuery = ref('');
const dropdownOpen = ref(false);
const openQuickAddModal = ref(false);
const quickLoading = ref(false);

const quickForm = ref({
    nama_lengkap: '',
    nidn: '',
    status: 'aktif',
});

// Selected objects list based on modelValue (IDs)
const selectedList = computed(() => {
    return props.modelValue
        .map(id => props.penelitis.find(p => p.id === id))
        .filter(Boolean);
});

// Filtered list from search query, excluding already selected
const filteredPenelitis = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    return props.penelitis
        .filter(p => !props.modelValue.includes(p.id))
        .filter(p => {
            if (!q) return true;
            return p.nama_lengkap.toLowerCase().includes(q) || (p.nidn && p.nidn.includes(q));
        })
        .slice(0, 10);
});

const selectPeneliti = (peneliti) => {
    const next = [...props.modelValue, peneliti.id];
    emit('update:modelValue', next);
    searchQuery.value = '';
    dropdownOpen.value = false;
};

const removePeneliti = (id) => {
    const next = props.modelValue.filter(val => val !== id);
    emit('update:modelValue', next);
};

const submitQuickAdd = async () => {
    quickLoading.value = true;
    try {
        const response = await fetch('/admin/peneliti', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(quickForm.value),
        });

        const data = await response.json();
        if (data.peneliti) {
            props.penelitis.push(data.peneliti);
            selectPeneliti(data.peneliti);
            quickForm.value = { nama_lengkap: '', nidn: '', status: 'aktif' };
            openQuickAddModal.value = false;
        }
    } catch (err) {
        console.error(err);
    } finally {
        quickLoading.value = false;
    }
};
</script>
