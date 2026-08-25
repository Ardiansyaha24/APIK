<template>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans">
        <!-- Main Header / Navbar (Compact Deep Navy) -->
        <header class="sticky top-0 z-40 bg-[#0F172A] border-b border-slate-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <!-- Brand Logo Simple & Clean -->
                    <div class="flex items-center gap-3">
                        <Link href="/" class="flex items-center gap-2.5 group">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                A
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-bold tracking-tight text-white group-hover:text-blue-300 transition-colors">APIK</span>
                                <span class="text-[11px] text-slate-400 font-medium hidden sm:inline border-l border-slate-700 pl-2">
                                    Akselerasi Penelitian, Inovasi & Kolaborasi
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- Navigation Links (Desktop - Visual Lengkap, Klik -> Modal Notice) -->
                    <nav class="hidden md:flex items-center gap-1 text-xs font-medium">
                        <Link 
                            href="/" 
                            class="px-3 py-1.5 rounded-lg bg-blue-600 text-white font-semibold flex items-center gap-1.5 transition-colors"
                        >
                            <Home class="w-3.5 h-3.5" />
                            Beranda
                        </Link>
                        <button 
                            type="button"
                            @click="showLockedNotice = true" 
                            class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 flex items-center gap-1.5 transition-colors"
                        >
                            <Compass class="w-3.5 h-3.5" />
                            Direktori Karya
                        </button>
                        <button 
                            type="button"
                            @click="showLockedNotice = true" 
                            class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 flex items-center gap-1.5 transition-colors"
                        >
                            <Users class="w-3.5 h-3.5" />
                            Daftar Peneliti
                        </button>
                        <button 
                            type="button"
                            @click="showLockedNotice = true" 
                            class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 flex items-center gap-1.5 transition-colors"
                        >
                            <BarChart3 class="w-3.5 h-3.5" />
                            Statistik
                        </button>
                    </nav>

                    <!-- Auth Actions -->
                    <div class="flex items-center gap-2">
                        <template v-if="$page.props.auth?.user">
                            <Link 
                                href="/admin" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-xs transition-colors"
                            >
                                <LayoutDashboard class="w-3.5 h-3.5" />
                                Panel Admin
                            </Link>
                        </template>
                        <template v-else>
                            <button 
                                type="button"
                                @click="showLockedNotice = true" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-medium transition-colors"
                            >
                                <Lock class="w-3.5 h-3.5 text-slate-400" />
                                <span>Masuk Admin</span>
                            </button>
                        </template>

                        <!-- Mobile Menu Button -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800"
                        >
                            <Menu v-if="!mobileMenuOpen" class="w-4 h-4" />
                            <X v-else class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div v-if="mobileMenuOpen" class="md:hidden py-2 border-t border-slate-800 space-y-1 text-xs">
                    <Link 
                        href="/" 
                        class="block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false"
                    >
                        Beranda
                    </Link>
                    <button 
                        type="button"
                        class="w-full text-left block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false; showLockedNotice = true"
                    >
                        Direktori Karya
                    </button>
                    <button 
                        type="button"
                        class="w-full text-left block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false; showLockedNotice = true"
                    >
                        Daftar Peneliti
                    </button>
                    <button 
                        type="button"
                        class="w-full text-left block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false; showLockedNotice = true"
                    >
                        Statistik
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1">
            <slot :openLockedNotice="() => showLockedNotice = true" />
        </main>

        <!-- Compact Footer -->
        <footer class="bg-white border-t border-slate-200 text-slate-500 text-xs py-6 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    <p class="font-bold text-slate-800">APIK — Akselerasi Penelitian, Inovasi, dan Kolaborasi</p>
                    <p class="text-[11px] text-slate-400">Pusat data terpadu Penelitian, Publikasi, PKM, Buku, dan Kekayaan Intelektual</p>
                </div>
                <div class="text-[11px] text-slate-400">
                    © {{ new Date().getFullYear() }} LP2M / Unit Riset & Publikasi.
                </div>
            </div>
        </footer>

        <!-- Modal Pemberitahuan Akses Admin Terkunci Sementara -->
        <Modal :show="showLockedNotice" @close="showLockedNotice = false" maxWidth="sm">
            <template #title>Portal Layanan APIK</template>
            <template #content>
                <div class="space-y-3 text-xs text-slate-600">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
                        <ShieldAlert class="w-6 h-6" />
                    </div>
                    <p class="font-bold text-slate-800 text-sm text-center">Dalam Pengembangan</p>
                    <p class="text-center text-slate-500 leading-relaxed">
                        Fitur ini sedang dalam proses sinkronisasi dan integrasi data internal LP2M.
                    </p>
                </div>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { Home, Compass, Users, BarChart3, LayoutDashboard, Lock, Menu, X, ShieldAlert } from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
const showLockedNotice = ref(false);
</script>
