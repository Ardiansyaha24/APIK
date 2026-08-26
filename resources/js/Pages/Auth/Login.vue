<template>
    <div class="min-h-screen bg-[#0F172A] flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden font-sans text-slate-800">
        <Head title="Masuk Panel Pengelola — APIK" />

        <!-- Background Glow -->
        <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:20px_20px] opacity-40 pointer-events-none"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 text-center space-y-3">
            <Link href="/" class="inline-flex items-center gap-2.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-xl shadow-blue-500/25 group-hover:scale-105 transition-transform">
                    <Sparkles class="w-6 h-6" />
                </div>
                <span class="text-2xl font-extrabold text-white tracking-tight">APIK</span>
            </Link>
            <h2 class="text-xl font-bold text-white tracking-tight">Masuk Panel Pengelola</h2>
            <p class="text-xs text-slate-400">Akses khusus administrator dan pengelola data LP2M</p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
            <div class="bg-white py-8 px-6 sm:px-8 shadow-2xl rounded-2xl border border-slate-200 space-y-4">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="email" 
                                v-model="form.email"
                                required
                                autofocus
                                placeholder="admin@apik.local"
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-[11px] text-rose-600 mt-1 font-medium">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="password" 
                                v-model="form.password"
                                required
                                placeholder="••••••••"
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                            />
                        </div>
                        <p v-if="form.errors.password" class="text-[11px] text-rose-600 mt-1 font-medium">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                            <input type="checkbox" v-model="form.remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                            <span>Ingat Saya</span>
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-500/20 transition-all text-center flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Masuk ke Panel Pengelola</span>
                    </button>
                </form>

                <div class="pt-2 text-center">
                    <Link href="/" class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                        ← Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Sparkles, Mail, Lock } from 'lucide-vue-next';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
