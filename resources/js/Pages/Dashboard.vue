<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
  Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale,
  LinearScale, PointElement, LineElement, ArcElement, Filler
} from 'chart.js';
import { Bar, Line, Doughnut } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Filler);

const props = defineProps({
    stats: Object,
    chart_data: Object,
    rekap_prodi: Array,
    recent_dosen: Array,
    recent_pegawai: Array,
});

// Utility to format date
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date);
};

// -- KPIs with fake sparkline data --
const kpis = computed(() => [
    {
        label: 'Total Dosen', value: props.stats.total_dosen,
        trend: '+5.2%', isUp: true,
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        color: 'text-emerald-600', bg: 'bg-emerald-50',
        chartData: {
            labels: ['1', '2', '3', '4', '5', '6'],
            datasets: [{ data: [30, 45, 38, 55, 48, 60], borderColor: '#059669', borderWidth: 2, tension: 0.4, pointRadius: 0 }]
        }
    },
    {
        label: 'Pegawai Struktural', value: props.stats.total_pegawai,
        trend: '+2.1%', isUp: true,
        icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        color: 'text-amber-600', bg: 'bg-amber-50',
        chartData: {
            labels: ['1', '2', '3', '4', '5', '6'],
            datasets: [{ data: [20, 22, 21, 25, 24, 28], borderColor: '#D97706', borderWidth: 2, tension: 0.4, pointRadius: 0 }]
        }
    },
    {
        label: 'Program Studi', value: props.stats.total_prodi,
        trend: 'Stabil', isUp: true,
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        color: 'text-indigo-600', bg: 'bg-indigo-50',
        chartData: {
            labels: ['1', '2', '3', '4', '5', '6'],
            datasets: [{ data: [15, 15, 15, 15, 15, 15], borderColor: '#4F46E5', borderWidth: 2, tension: 0.4, pointRadius: 0 }]
        }
    },
    {
        label: 'Dosen Bergelar S3', value: props.stats.dosen_s3,
        trend: '+12.5%', isUp: true,
        icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
        color: 'text-sky-600', bg: 'bg-sky-50',
        chartData: {
            labels: ['1', '2', '3', '4', '5', '6'],
            datasets: [{ data: props.chart_data.trend_s3, borderColor: '#0284C7', borderWidth: 2, tension: 0.4, pointRadius: 0 }]
        }
    }
]);

const sparklineOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { enabled: false } },
    scales: { x: { display: false }, y: { display: false, min: 0 } },
    layout: { padding: 0 }
};

// -- Rekap Prodi Bar Chart --
const rekapProdiChartData = computed(() => {
    return {
        labels: props.rekap_prodi.map(p => p.program_studi.length > 20 ? p.program_studi.substring(0, 20) + '...' : p.program_studi),
        datasets: [
            { label: 'Total Dosen', backgroundColor: '#94a3b8', data: props.rekap_prodi.map(p => p.jumlah_dosen) },
            { label: 'S3', backgroundColor: '#10b981', data: props.rekap_prodi.map(p => p.jumlah_doktor) },
            { label: 'Guru Besar', backgroundColor: '#eab308', data: props.rekap_prodi.map(p => p.jumlah_guru_besar) },
        ]
    };
});
const rekapProdiOptions = {
    responsive: true, maintainAspectRatio: false, indexAxis: 'y',
    plugins: { legend: { position: 'bottom' } },
    scales: { x: { stacked: false }, y: { stacked: false } }
};

// -- Trend S3 Line Chart --
const trendS3ChartData = computed(() => {
    return {
        labels: ['Bulan 1', 'Bulan 2', 'Bulan 3', 'Bulan 4', 'Bulan 5', 'Bulan 6'],
        datasets: [{
            label: 'Pertumbuhan Dosen S3',
            data: props.chart_data.trend_s3,
            borderColor: '#996600',
            backgroundColor: 'rgba(153, 102, 0, 0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };
});
const trendS3Options = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } }
};

// -- Donut: BRI Ownership --
const briDonutData = computed(() => {
    return {
        labels: ['Sudah Punya', 'Belum Punya'],
        datasets: [{
            data: [props.chart_data.bri_ownership.sudah, props.chart_data.bri_ownership.belum],
            backgroundColor: ['#10b981', '#f43f5e'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    };
});

// -- Donut: Pendidikan --
const pendidikanDonutData = computed(() => {
    const labels = Object.keys(props.chart_data.pendidikan);
    const data = Object.values(props.chart_data.pendidikan);
    return {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: ['#996600', '#d97706', '#f59e0b', '#fbbf24', '#fcd34d', '#94a3b8'],
            borderWidth: 0,
        }]
    };
});

// -- Donut: Jabatan Akademik --
const jabatanDonutData = computed(() => {
    const guruBesar = props.stats.guru_besar || 0;
    const lainnya = (props.stats.total_dosen || 0) - guruBesar;
    return {
        labels: ['Guru Besar', 'Lainnya'],
        datasets: [{
            data: [guruBesar, lainnya],
            backgroundColor: ['#eab308', '#94a3b8'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    };
});

const donutOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
    },
    cutout: '70%'
};

const totalPendidikan = computed(() => {
    return Object.values(props.chart_data.pendidikan).reduce((a, b) => a + b, 0);
});

const persentaseBri = computed(() => {
    const sudah = props.chart_data.bri_ownership.sudah;
    const belum = props.chart_data.bri_ownership.belum;
    const total = sudah + belum;
    return total === 0 ? 0 : Math.round((sudah / total) * 100);
});
</script>

<template>
    <PengelolaLayout>
        <Head title="Dashboard Analitik" />
        <template #header>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Dashboard Analitik</h1>
                <p class="text-xs text-gray-500 mt-1">Ringkasan Sistem Kepegawaian IKIP Siliwangi secara real-time.</p>
            </div>
        </template>

        <!-- KPI CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
            <div v-for="(kpi, i) in kpis" :key="i" class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden group hover:shadow-md transition-shadow">
                <!-- Data Content Layer (Above Graph) -->
                <div class="p-5 relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div :class="[kpi.bg, kpi.color]" class="p-3 rounded-xl">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="kpi.icon" />
                            </svg>
                        </div>
                        <span :class="kpi.isUp ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50'" class="text-xs font-semibold px-2 py-1 rounded-md flex items-center gap-1">
                            <svg v-if="kpi.isUp" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            {{ kpi.trend }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">{{ kpi.value }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ kpi.label }}</p>
                    </div>
                </div>
                <!-- Sparkline Layer (Bottom Background) -->
                <div class="absolute bottom-0 left-0 right-0 h-24 opacity-20 pointer-events-none -mb-4 z-0">
                    <Line :data="kpi.chartData" :options="sparklineOptions" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <!-- REKAP PRODI CHART (Left 2/3) -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Bar Chart: Distribusi Prodi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-800">Distribusi Dosen per Program Studi</h2>
                        <Link :href="route('rekap-prodi.index')" class="text-xs font-medium text-[#996600] hover:underline bg-[#996600]/10 px-3 py-1.5 rounded-lg">Kelola Prodi</Link>
                    </div>
                    <div class="h-80">
                        <Bar :data="rekapProdiChartData" :options="rekapProdiOptions" />
                    </div>
                </div>

                <!-- Line Chart: Trend S3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 mb-4">Tren Pertumbuhan Dosen S3</h2>
                    <div class="h-60">
                        <Line :data="trendS3ChartData" :options="trendS3Options" />
                    </div>
                </div>
            </div>

            <!-- DISTRIBUSI & STATUS (Right 1/3) -->
            <div class="space-y-6">
                <!-- Donut: Pendidikan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 mb-4">Sebaran Tingkat Pendidikan</h2>
                    <div class="h-56 relative">
                        <Doughnut :data="pendidikanDonutData" :options="donutOptions" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-8">
                            <span class="text-2xl font-bold text-gray-800">{{ totalPendidikan }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Total</span>
                        </div>
                    </div>
                </div>

                <!-- Donut: BRI -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 mb-4">Kepemilikan Rekening BRI</h2>
                    <div class="h-56 relative">
                        <Doughnut :data="briDonutData" :options="donutOptions" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-8">
                            <span class="text-2xl font-bold text-gray-800">{{ persentaseBri }}%</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Sudah Punya</span>
                        </div>
                    </div>
                </div>

                <!-- Donut: Jabatan Akademik -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 mb-4">Jabatan Akademik Dosen</h2>
                    <div class="h-56 relative">
                        <Doughnut :data="jabatanDonutData" :options="donutOptions" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-8">
                            <span class="text-2xl font-bold text-gray-800">{{ stats.guru_besar || 0 }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Guru Besar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTIVITY PANEL -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Dosen Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[400px]">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <h2 class="font-bold text-gray-800">Dosen Terbaru Diperbarui</h2>
                    <Link :href="route('dosen.index')" class="text-xs font-medium text-[#996600] hover:underline">Lihat Semua</Link>
                </div>
                <div class="divide-y divide-gray-50 overflow-y-auto flex-1">
                    <div v-for="d in recent_dosen" :key="d.id" class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
                        <img v-if="d.foto" :src="d.foto" class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm" />
                        <div v-else class="h-10 w-10 rounded-full bg-gradient-to-br from-[#996600] to-yellow-600 flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                            {{ d.nama.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-800 truncate">{{ d.nama }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ d.homebase_prodi || 'Prodi belum diatur' }} • {{ d.pendidikan || '-' }}</p>
                        </div>
                        <div class="text-[10px] text-gray-400 text-right whitespace-nowrap">
                            {{ formatDate(d.updated_at) }}
                        </div>
                    </div>
                    <div v-if="!recent_dosen.length" class="p-6 text-center text-gray-400 text-sm">Belum ada data dosen.</div>
                </div>
            </div>

            <!-- Pegawai Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[400px]">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <h2 class="font-bold text-gray-800">Pegawai Terbaru Diperbarui</h2>
                    <Link :href="route('pegawai-struktural.index')" class="text-xs font-medium text-[#996600] hover:underline">Lihat Semua</Link>
                </div>
                <div class="divide-y divide-gray-50 overflow-y-auto flex-1">
                    <div v-for="p in recent_pegawai" :key="p.id" class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
                        <img v-if="p.foto" :src="p.foto" class="h-10 w-10 rounded-full object-cover border border-gray-100 shadow-sm" />
                        <div v-else class="h-10 w-10 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                            {{ p.nama.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-800 truncate">{{ p.nama }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ p.jabatan || 'Jabatan belum diatur' }}</p>
                        </div>
                        <div class="text-[10px] text-gray-400 text-right whitespace-nowrap">
                            {{ formatDate(p.updated_at) }}
                        </div>
                    </div>
                    <div v-if="!recent_pegawai.length" class="p-6 text-center text-gray-400 text-sm">Belum ada data pegawai.</div>
                </div>
            </div>
        </div>
    </PengelolaLayout>
</template>
