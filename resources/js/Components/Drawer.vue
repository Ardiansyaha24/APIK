<template>
    <teleport to="body">
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 overflow-hidden bg-slate-900/50 backdrop-blur-xs flex justify-end">
                <div 
                    class="w-full max-w-xl bg-white h-full shadow-2xl flex flex-col transform transition-all duration-300 ease-in-out border-l border-slate-200"
                    @click.stop
                >
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                <slot name="title">Form Input</slot>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                <slot name="subtitle">Lengkapi formulir di bawah ini</slot>
                            </p>
                        </div>
                        <button 
                            @click="$emit('close')" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/70 transition-colors"
                        >
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Scrollable Body -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-5">
                        <slot name="content" />
                    </div>

                    <!-- Footer / Actions -->
                    <div v-if="$slots.footer" class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
import { X } from 'lucide-vue-next';

defineProps({
    show: { type: Boolean, default: false },
});

defineEmits(['close']);
</script>
