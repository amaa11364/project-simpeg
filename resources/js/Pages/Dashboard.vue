<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    rekap_prodi: Array,
    recent_dosen: Array,
    recent_pegawai: Array,
});

const statCards = [
    { label: 'Total Dosen', value: props.stats.total_dosen, icon: 'M12 14l9-5-9-5-9 5 9 5z', color: 'bg-blue-500', bg: 'bg-blue-50', text: 'text-blue-600' },
    { label: 'Pegawai Struktural', value: props.stats.total_pegawai, icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', color: 'bg-amber-500', bg: 'bg-amber-50', text: 'text-amber-600' },
    { label: 'Program Studi', value: props.stats.total_prodi, icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', color: 'bg-purple-500', bg: 'bg-purple-50', text: 'text-purple-600' },
    { label: 'Dosen Bergelar S3', value: props.stats.dosen_s3, icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', color: 'bg-green-500', bg: 'bg-green-50', text: 'text-green-600' },
];
</script>

<template>
    <PengelolaLayout>
        <Head title="Dashboard" />
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
        </template>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
            <div v-for="card in statCards" :key="card.label"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div :class="[card.bg, 'p-3 rounded-xl']">
                    <svg :class="[card.text, 'h-6 w-6']" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ card.value }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ card.label }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Rekap Prodi Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Rekap Per Program Studi</h2>
                    <a :href="route('rekap-prodi.index')" class="text-xs text-[#996600] hover:underline">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <th class="px-4 py-3 text-left">Program Studi</th>
                                <th class="px-4 py-3 text-center">Dosen</th>
                                <th class="px-4 py-3 text-center">S3</th>
                                <th class="px-4 py-3 text-center">GB</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="r in rekap_prodi" :key="r.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-800">{{ r.program_studi }}</td>
                                <td class="px-4 py-3 text-center font-medium text-blue-600">{{ r.jumlah_dosen }}</td>
                                <td class="px-4 py-3 text-center font-medium text-green-600">{{ r.jumlah_doktor }}</td>
                                <td class="px-4 py-3 text-center font-medium text-purple-600">{{ r.jumlah_guru_besar }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-amber-50 font-semibold text-sm">
                                <td class="px-4 py-3 text-gray-700">Total</td>
                                <td class="px-4 py-3 text-center text-blue-700">{{ rekap_prodi.reduce((s,r) => s + r.jumlah_dosen, 0) }}</td>
                                <td class="px-4 py-3 text-center text-green-700">{{ rekap_prodi.reduce((s,r) => s + r.jumlah_doktor, 0) }}</td>
                                <td class="px-4 py-3 text-center text-purple-700">{{ rekap_prodi.reduce((s,r) => s + r.jumlah_guru_besar, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Kepemilikan Rekening BRI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Kepemilikan Rekening BRI</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Dosen - Sudah Punya</span>
                            <span class="font-semibold text-gray-800">{{ stats.dosen_rek_bri }} / {{ stats.total_dosen }}</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full transition-all"
                                :style="{ width: stats.total_dosen ? (stats.dosen_rek_bri / stats.total_dosen * 100) + '%' : '0%' }">
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">{{ stats.total_dosen ? Math.round(stats.dosen_rek_bri / stats.total_dosen * 100) : 0 }}% sudah memiliki rekening</div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Pegawai Struktural - Sudah Punya</span>
                            <span class="font-semibold text-gray-800">{{ stats.pegawai_rek_bri }} / {{ stats.total_pegawai }}</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all"
                                :style="{ width: stats.total_pegawai ? (stats.pegawai_rek_bri / stats.total_pegawai * 100) + '%' : '0%' }">
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">{{ stats.total_pegawai ? Math.round(stats.pegawai_rek_bri / stats.total_pegawai * 100) : 0 }}% sudah memiliki rekening</div>
                    </div>
                </div>

                <!-- Recent Pegawai -->
                <div class="px-5 pb-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Pegawai Terbaru</h3>
                    <div class="space-y-2">
                        <div v-for="p in recent_pegawai" :key="p.id"
                            class="flex items-center gap-3 text-sm">
                            <div class="h-7 w-7 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-xs shrink-0">
                                {{ p.nama.charAt(0) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-gray-800 truncate">{{ p.nama }}</div>
                                <div class="text-xs text-gray-400">{{ p.jabatan }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Dosen -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Dosen Terbaru</h2>
                <a :href="route('dosen.index')" class="text-xs text-[#996600] hover:underline">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">NIDN/NIK</th>
                            <th class="px-4 py-3 text-left">Prodi</th>
                            <th class="px-4 py-3 text-center">Pendidikan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="d in recent_dosen" :key="d.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ d.nama }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ d.nidn_nik || '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ d.homebase_prodi || '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="{
                                    'bg-green-100 text-green-700': d.pendidikan === 'S3',
                                    'bg-blue-100 text-blue-700': d.pendidikan === 'S2',
                                    'bg-gray-100 text-gray-700': !['S3','S2'].includes(d.pendidikan)
                                }" class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ d.pendidikan || '-' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PengelolaLayout>
</template>
