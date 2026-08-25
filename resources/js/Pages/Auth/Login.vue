<template>
    <div class="min-h-screen bg-[#0F172A] flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden font-sans text-slate-800">
        <Head title="Masuk Panel Pengelola" />

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
            <p class="text-xs text-slate-400">Akses khusus administrator dan operator LP2M</p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
            <div class="bg-white py-8 px-6 sm:px-8 shadow-2xl rounded-2xl border border-slate-200 space-y-4">
                <!-- Lock Announcement Banner -->
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
                    <ShieldAlert class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="font-bold">Akses Masuk Terkunci Sementara</p>
                        <p class="text-[11px] text-amber-700 mt-0.5 leading-relaxed">
                            Proses autentikasi pengelola ditutup sementara selama fase peninjauan & integrasi data awal.
                        </p>
                    </div>
                </div>

                <form class="space-y-4 opacity-60 pointer-events-none">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="email" 
                                disabled
                                placeholder="admin@apik.local"
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm bg-slate-50 outline-none cursor-not-allowed"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" />
                            <input 
                                type="password" 
                                disabled
                                placeholder="••••••••"
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm bg-slate-50 outline-none cursor-not-allowed"
                            />
                        </div>
                    </div>

                    <button 
                        type="button" 
                        disabled
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-slate-300 text-slate-500 cursor-not-allowed text-center"
                    >
                        Akses Belum Dibuka
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
import { Head, Link } from '@inertiajs/vue3';
import { Sparkles, Mail, Lock, ShieldAlert } from 'lucide-vue-next';
</script>
