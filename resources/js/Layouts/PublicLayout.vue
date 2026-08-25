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

                    <!-- Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center gap-1 text-xs font-medium">
                        <Link 
                            href="/" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url === '/' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Home class="w-3.5 h-3.5" />
                            Beranda
                        </Link>
                        <Link 
                            href="/direktori" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/direktori') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Compass class="w-3.5 h-3.5" />
                            Direktori Karya
                        </Link>
                        <Link 
                            href="/peneliti" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/peneliti') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Users class="w-3.5 h-3.5" />
                            Daftar Peneliti
                        </Link>
                        <Link 
                            href="/statistik" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/statistik') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <BarChart3 class="w-3.5 h-3.5" />
                            Statistik
                        </Link>
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
                    <Link 
                        href="/direktori" 
                        class="block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false"
                    >
                        Direktori Karya
                    </Link>
                    <Link 
                        href="/peneliti" 
                        class="block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false"
                    >
                        Daftar Peneliti
                    </Link>
                    <Link 
                        href="/statistik" 
                        class="block px-3 py-1.5 rounded text-slate-200 hover:bg-slate-800"
                        @click="mobileMenuOpen = false"
                    >
                        Statistik
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Modal Pemberitahuan Akses Admin Terkunci Sementara -->
        <Modal :show="showLockedNotice" @close="showLockedNotice = false" maxWidth="sm">
            <template #title>Portal Pengelola LP2M</template>
            <template #content>
                <div class="space-y-3 text-xs text-slate-600">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
                        <ShieldAlert class="w-6 h-6" />
                    </div>
                    <p class="font-bold text-slate-800 text-sm text-center">Dalam Pengembangan</p>
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

