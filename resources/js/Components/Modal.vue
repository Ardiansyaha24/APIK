<template>
    <teleport to="body">
        <transition 
            enter-active-class="transition duration-200 ease-out" 
            enter-from-class="opacity-0" 
            enter-to-class="opacity-100" 
            leave-active-class="transition duration-150 ease-in" 
            leave-from-class="opacity-100" 
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div 
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200 transform transition-all"
                    :class="maxWidthClass"
                    @click.stop
                >
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-base font-semibold text-slate-800">
                            <slot name="title">Modal Header</slot>
                        </h3>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-6">
                        <slot name="content" />
                    </div>

                    <div v-if="$slots.footer" class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
import { computed } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    show: { type: Boolean, default: false },
    maxWidth: { type: String, default: 'md' },
});

defineEmits(['close']);

const maxWidthClass = computed(() => {
    return {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
        '2xl': 'max-w-2xl',
        '3xl': 'max-w-3xl',
    }[props.maxWidth] || 'max-w-md';
});
</script>
