<template>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-100 selection:text-blue-900">
        <!-- Compact Navbar -->
        <header class="bg-[#0F172A] border-b border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <!-- Brand / Logo -->
                    <Link href="/" class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            A
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-sm tracking-tight text-white leading-none">APIK</span>
                            <span class="text-[10px] text-slate-400 font-medium leading-tight">LP2M IAIN Manado</span>
                        </div>
                    </Link>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 text-xs font-medium">
                        <Link 
                            href="/" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url === '/' 
                                    ? 'bg-blue-600 text-white font-semibold shadow-xs' 
                                    : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Home class="w-3.5 h-3.5" />
                            <span>Beranda</span>
                        </Link>

                        <Link 
                            href="/direktori" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/direktori') 
                                    ? 'bg-blue-600 text-white font-semibold shadow-xs' 
                                    : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Compass class="w-3.5 h-3.5" />
                            <span>Direktori Karya</span>
                        </Link>

                        <Link 
                            href="/peneliti" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/peneliti') 
                                    ? 'bg-blue-600 text-white font-semibold shadow-xs' 
                                    : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Users class="w-3.5 h-3.5" />
                            <span>Daftar Peneliti</span>
                        </Link>

                        <Link 
                            href="/statistik" 
                            :class="[
                                'px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5',
                                $page.url.startsWith('/statistik') 
                                    ? 'bg-blue-600 text-white font-semibold shadow-xs' 
                                    : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <BarChart3 class="w-3.5 h-3.5" />
                            <span>Statistik</span>
                        </Link>

                        <a 
                            href="https://lp2m.iain-manado.ac.id" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5 text-slate-300 hover:text-white hover:bg-slate-800"
                        >
                            <Globe class="w-3.5 h-3.5 text-blue-400" />
                            <span>Website LP2M</span>
                            <ExternalLink class="w-3 h-3 text-slate-400" />
                        </a>
                    </nav>

                    <!-- Auth Actions -->
                    <div class="flex items-center gap-2">
                        <template v-if="$page.props.auth?.user">
                            <Link 
                                href="/admin" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-xs transition-colors"
                            >
                                <LayoutDashboard class="w-3.5 h-3.5" />
                                <span>Panel Admin</span>
                            </Link>
                        </template>
                        <template v-else>
                            <Link 
                                href="/login" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-medium transition-colors"
                            >
                                <Lock class="w-3.5 h-3.5 text-slate-400" />
                                <span>Masuk Admin</span>
                            </Link>
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
                    <a 
                        href="https://lp2m.iain-manado.ac.id" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="block px-3 py-1.5 rounded text-blue-400 hover:bg-slate-800 flex items-center justify-between"
                        @click="mobileMenuOpen = false"
                    >
                        <span>Website LP2M</span>
                        <ExternalLink class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Compact Footer -->
        <footer class="bg-white border-t border-slate-200 text-slate-500 text-xs py-6 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    <p class="font-bold text-slate-800">APIK — Akselerasi Penelitian, Inovasi, dan Kolaborasi</p>
                </div>
                <div class="text-[11px] text-slate-400">
                    © {{ new Date().getFullYear() }} Lembaga Penelitian dan Pengabdian Masyarakat (LP2M).
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Home, Compass, Users, BarChart3, LayoutDashboard, Lock, Menu, X, Globe, ExternalLink } from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
</script>
