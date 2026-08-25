<template>
    <PublicLayout>
        <Head title="Statistik & Analitik Riset" />

        <!-- Header Hero (Deep Navy) -->
        <section class="bg-[#0F172A] text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
            <div class="max-w-5xl mx-auto text-center space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-medium">
                    <BarChart3 class="w-3.5 h-3.5" />
                    Analitik & Kinerja Produktivitas
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Statistik & Kinerja Riset</h1>
                <p class="text-sm text-slate-300 max-w-xl mx-auto">
                    Transparansi metrik pertumbuhan karya ilmiah, jurnal terindeks, dan pengabdian masyarakat sivitas akademika.
                </p>
            </div>
        </section>

        <!-- Main Content (Bento Grid) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            <!-- Counter Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-slate-500">Total Luaran</p>
                    <p class="text-2xl font-extrabold text-[#0F172A] mt-1">{{ counts.total_karya }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-emerald-600">Penelitian</p>
                    <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ counts.total_penelitian }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-blue-600">Publikasi</p>
                    <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ counts.total_publikasi }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-amber-600">PKM</p>
                    <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ counts.total_pkm }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-violet-600">Buku</p>
                    <p class="text-2xl font-extrabold text-violet-700 mt-1">{{ counts.total_buku }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs text-center">
                    <p class="text-xs font-semibold text-cyan-600">HKI / Paten</p>
                    <p class="text-2xl font-extrabold text-cyan-700 mt-1">{{ counts.total_haki }}</p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Trend Chart (Area Line) -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Tren Pertumbuhan Karya per Tahun</h2>
                            <p class="text-xs text-slate-500">Distribusi seluruh modul karya ilmiah berdasarkan tahun pelaksanaan</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            Tahunan
                        </span>
                    </div>

                    <div class="h-80">
                        <apexchart 
                            type="area" 
                            height="100%" 
                            :options="trendChartOptions" 
                            :series="trendSeries" 
                        />
                    </div>
                </div>

                <!-- Publication Index Distribution (Donut) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4 flex flex-col justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Distribusi Publikasi</h2>
                        <p class="text-xs text-slate-500">Proporsi indeks SINTA, Scopus, dan Prosiding</p>
                    </div>

                    <div class="h-64 flex items-center justify-center">
                        <apexchart 
                            type="donut" 
                            height="100%" 
                            width="100%"
                            :options="donutChartOptions" 
                            :series="pubDistribution.series" 
                        />
                    </div>

                    <p class="text-[11px] text-slate-400 text-center">
                        Data diperbarui otomatis dari database luaran publikasi
                    </p>
                </div>
            </div>

            <!-- Leaderboard Top Peneliti -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <Trophy class="w-4 h-4 text-amber-500" />
                            Top Peneliti & Dosen Terproduktif
                        </h2>
                        <p class="text-xs text-slate-500">Peringkat produktivitas akumulasi seluruh luaran karya ilmiah</p>
                    </div>
                    <Link href="/peneliti" class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
                        <span>Lihat Semua Dosen</span>
                        <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-y border-slate-200">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">#</th>
                                <th class="py-3 px-4">Nama Peneliti</th>
                                <th class="py-3 px-4">Program Studi & Fakultas</th>
                                <th class="py-3 px-4 text-center">Penelitian</th>
                                <th class="py-3 px-4 text-center">Publikasi</th>
                                <th class="py-3 px-4 text-center">HKI</th>
                                <th class="py-3 px-4 text-right">Total Luaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr v-for="(p, idx) in topPeneliti" :key="p.id" class="hover:bg-blue-50/40 transition-colors">
                                <td class="py-3 px-4 text-center">
                                    <span 
                                        :class="[
                                            'w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-[11px]',
                                            idx === 0 ? 'bg-amber-100 text-amber-800' :
                                            idx === 1 ? 'bg-slate-200 text-slate-800' :
                                            idx === 2 ? 'bg-amber-50 text-amber-700' : 'text-slate-500'
                                        ]"
                                    >
                                        {{ idx + 1 }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <Link :href="`/peneliti/${p.id}`" class="font-bold text-slate-900 hover:text-blue-600">
                                        {{ p.nama_lengkap }}
                                    </Link>
                                    <p class="text-[10px] text-slate-400 font-mono">NIDN: {{ p.nidn || '-' }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    <p>{{ p.prodi }}</p>
                                    <p class="text-[10px] text-slate-400">{{ p.fakultas }}</p>
                                </td>
                                <td class="py-3 px-4 text-center font-mono">{{ p.penelitian_count }}</td>
                                <td class="py-3 px-4 text-center font-mono">{{ p.publikasi_count }}</td>
                                <td class="py-3 px-4 text-center font-mono">{{ p.haki_count }}</td>
                                <td class="py-3 px-4 text-right">
                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold font-mono">
                                        {{ p.total_karya }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { BarChart3, Trophy, ArrowRight } from 'lucide-vue-next';

const props = defineProps({
    trendData: { type: Object, required: true },
    pubDistribution: { type: Object, required: true },
    topPeneliti: { type: Array, default: () => [] },
    counts: { type: Object, required: true },
});

const trendSeries = computed(() => [
    { name: 'Penelitian', data: props.trendData.penelitian },
    { name: 'Publikasi', data: props.trendData.publikasi },
    { name: 'PKM', data: props.trendData.pkm },
    { name: 'HKI & Paten', data: props.trendData.haki },
    { name: 'Buku', data: props.trendData.buku },
]);

const trendChartOptions = computed(() => ({
    chart: {
        type: 'area',
        toolbar: { show: false },
        fontFamily: 'Plus Jakarta Sans, sans-serif',
    },
    colors: ['#059669', '#2563eb', '#d97706', '#06b6d4', '#7c3aed'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [20, 100],
        },
    },
    xaxis: {
        categories: props.trendData.years,
        labels: { style: { colors: '#64748b', fontSize: '11px' } },
    },
    yaxis: {
        labels: { style: { colors: '#64748b', fontSize: '11px' } },
    },
    tooltip: { theme: 'light' },
    grid: { borderColor: '#f1f5f9' },
}));

const donutChartOptions = computed(() => ({
    chart: {
        type: 'donut',
        fontFamily: 'Plus Jakarta Sans, sans-serif',
    },
    labels: props.pubDistribution.labels,
    colors: ['#2563eb', '#059669', '#0284c7', '#d97706', '#7c3aed'],
    legend: { position: 'bottom', fontSize: '11px', labels: { colors: '#64748b' } },
    dataLabels: { enabled: false },
    tooltip: { theme: 'light' },
}));
</script>
