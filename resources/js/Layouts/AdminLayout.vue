<template>
    <div class="min-h-screen bg-slate-100 flex font-sans text-slate-800">
        <!-- Sidebar Navigation (Deep Navy) -->
        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-[#0F172A] border-r border-slate-800 flex flex-col transition-transform duration-200 lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Sidebar Header / Logo -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800">
                <Link href="/admin" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-600/30">
                        <Sparkles class="w-4 h-4" />
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-white tracking-tight">APIK</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 font-semibold">Admin</span>
                        </div>
                    </div>
                </Link>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Sidebar Navigation Links -->
            <div class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
                <div>
                    <p class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Utama</p>
                    <nav class="space-y-1">
                        <Link 
                            href="/admin" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <LayoutDashboard class="w-4 h-4" />
                            Dashboard
                        </Link>
                        <Link 
                            href="/admin/peneliti" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/peneliti') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Users class="w-4 h-4" />
                            Master Peneliti
                        </Link>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Modul Output Ilmiah</p>
                    <nav class="space-y-1">
                        <Link 
                            href="/admin/penelitian" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/penelitian') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <FlaskConical class="w-4 h-4 text-emerald-400" />
                            Penelitian
                        </Link>
                        <Link 
                            href="/admin/publikasi" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/publikasi') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <FileText class="w-4 h-4 text-blue-400" />
                            Publikasi (Jurnal/Prosiding)
                        </Link>
                        <Link 
                            href="/admin/pkm" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/pkm') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <HandHeart class="w-4 h-4 text-amber-400" />
                            Pengabdian (PKM)
                        </Link>
                        <Link 
                            href="/admin/buku" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/buku') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <BookOpen class="w-4 h-4 text-violet-400" />
                            Buku
                        </Link>
                        <Link 
                            href="/admin/haki" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/haki') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Award class="w-4 h-4 text-cyan-400" />
                            HKI & Paten
                        </Link>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Konfigurasi</p>
                    <nav class="space-y-1">
                        <Link 
                            href="/admin/master" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                                isRouteActive($page.url, '/admin/master') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800'
                            ]"
                        >
                            <Database class="w-4 h-4" />
                            Master Data (Prodi & Skema)
                        </Link>
                    </nav>
                </div>
            </div>

            <!-- Sidebar Footer (Public Link & User Profile) -->
            <div class="p-3 border-t border-slate-800 space-y-2">
                <Link 
                    href="/" 
                    target="_blank" 
                    class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                >
                    <span class="flex items-center gap-2">
                        <Globe class="w-3.5 h-3.5" />
                        Lihat Portal Publik
                    </span>
                    <ExternalLink class="w-3 h-3" />
                </Link>

                <div class="flex items-center justify-between px-3 py-2.5 rounded-lg bg-slate-800/80 border border-slate-700/50">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                            {{ $page.props.auth?.user?.name?.charAt(0) || 'A' }}
                        </div>
                        <div class="truncate text-left">
                            <p class="text-xs font-medium text-white truncate">{{ $page.props.auth?.user?.name || 'Administrator' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ $page.props.auth?.user?.email || 'admin@apik.local' }}</p>
                        </div>
                    </div>
                    <button 
                        @click="handleLogout" 
                        class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-colors"
                        title="Keluar"
                    >
                        <LogOut class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </aside>

        <!-- Backdrop for mobile -->
        <div 
            v-if="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden"
        ></div>

        <!-- Main Wrapper -->
        <div class="flex-1 lg:pl-64 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <button 
                        @click="sidebarOpen = true" 
                        class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                    <h1 class="text-sm font-semibold text-slate-800">
                        <slot name="header">Panel Pengelola APIK</slot>
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Sistem Aktif (SQLite)
                    </span>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Notification Banner -->
                <div v-if="$page.props.flash?.success" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <CheckCircle class="w-5 h-5 text-emerald-600 shrink-0" />
                        <span>{{ $page.props.flash.success }}</span>
                    </div>
                    <button @click="$page.props.flash.success = null" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <div v-if="$page.props.flash?.error" class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <AlertTriangle class="w-5 h-5 text-rose-600 shrink-0" />
                        <span>{{ $page.props.flash.error }}</span>
                    </div>
                    <button @click="$page.props.flash.error = null" class="text-rose-500 hover:text-rose-700 p-1">
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { 
    Sparkles, LayoutDashboard, Users, FlaskConical, 
    FileText, HandHeart, BookOpen, Award, Database, 
    Globe, ExternalLink, LogOut, Menu, X, CheckCircle, AlertTriangle 
} from 'lucide-vue-next';

const sidebarOpen = ref(false);

const isRouteActive = (currentUrl, prefix) => {
    if (!currentUrl) return false;
    const base = currentUrl.split('?')[0];
    if (prefix === '/admin') {
        return base === '/admin' || base === '/admin/';
    }
    return base === prefix || base.startsWith(prefix + '/');
};

const handleLogout = () => {
    if (confirm('Apakah Anda yakin ingin keluar dari panel admin?')) {
        router.post('/logout');
    }
};
</script>
