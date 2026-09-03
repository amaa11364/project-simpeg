<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ rekap: Array });

const sort = ref('program_studi');
const dir  = ref('asc');

const sortBy = (col) => {
    if (sort.value === col) {
        dir.value = dir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sort.value = col;
        dir.value = 'asc';
    }
};

const sorted = computed(() => {
    return [...props.rekap].sort((a, b) => {
        const av = a[sort.value] ?? 0;
        const bv = b[sort.value] ?? 0;
        if (typeof av === 'string') {
            return dir.value === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
        }
        return dir.value === 'asc' ? av - bv : bv - av;
    });
});

const totals = computed(() => ({
    dosen:    props.rekap.reduce((s, r) => s + r.jumlah_dosen, 0),
    gb:       props.rekap.reduce((s, r) => s + r.jumlah_guru_besar, 0),
    doktor:   props.rekap.reduce((s, r) => s + r.jumlah_doktor, 0),
    magister: props.rekap.reduce((s, r) => s + r.jumlah_magister, 0),
}));

const exportOpen = ref(false);
</script>

<template>
    <PengelolaLayout>
        <Head title="Rekap Program Studi" />
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Rekap Program Studi</h1>
        </template>

        <div class="space-y-4">
            <!-- Hanya tombol ekspor, read only -->
            <div class="flex justify-end">
                <div class="relative">
                    <button @click="exportOpen = !exportOpen"
                        class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Ekspor
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div v-if="exportOpen" class="fixed inset-0 z-0" @click="exportOpen = false"></div>
                    <div v-if="exportOpen" class="absolute right-0 mt-1 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                        <a :href="route('export.rekap.csv')" @click="exportOpen = false"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Unduh CSV
                        </a>
                        <a :href="route('export.rekap.pdf')" @click="exportOpen = false"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 border-t border-gray-50">
                            <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- MOBILE: Card -->
                <div class="sm:hidden divide-y divide-gray-100">
                    <div v-for="(r, i) in sorted" :key="r.id" class="p-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ r.program_studi }}</p>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2 text-xs">
                            <span class="text-gray-500">Dosen: <span class="font-semibold text-blue-600">{{ r.jumlah_dosen }}</span></span>
                            <span class="text-gray-500">Guru Besar: <span class="font-semibold text-purple-600">{{ r.jumlah_guru_besar }}</span></span>
                            <span class="text-gray-500">Doktor: <span class="font-semibold text-green-600">{{ r.jumlah_doktor }}</span></span>
                            <span class="text-gray-500">Magister: <span class="font-semibold text-amber-600">{{ r.jumlah_magister }}</span></span>
                        </div>
                    </div>
                    <div v-if="rekap.length === 0" class="p-10 text-center text-gray-400 text-sm">Tidak ada data</div>
                </div>

                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                                <th class="px-4 py-3 text-left w-8">#</th>
                                <th class="px-4 py-3 text-left cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('program_studi')">
                                    <span class="flex items-center gap-1">Program Studi
                                        <svg class="h-3 w-3" :class="sort==='program_studi' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='program_studi' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='program_studi' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('jumlah_dosen')">
                                    <span class="flex items-center justify-center gap-1">Jml. Dosen
                                        <svg class="h-3 w-3" :class="sort==='jumlah_dosen' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='jumlah_dosen' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='jumlah_dosen' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('jumlah_guru_besar')">
                                    <span class="flex items-center justify-center gap-1">Guru Besar
                                        <svg class="h-3 w-3" :class="sort==='jumlah_guru_besar' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='jumlah_guru_besar' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='jumlah_guru_besar' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('jumlah_doktor')">
                                    <span class="flex items-center justify-center gap-1">Doktor
                                        <svg class="h-3 w-3" :class="sort==='jumlah_doktor' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='jumlah_doktor' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='jumlah_doktor' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('jumlah_magister')">
                                    <span class="flex items-center justify-center gap-1">Magister
                                        <svg class="h-3 w-3" :class="sort==='jumlah_magister' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='jumlah_magister' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='jumlah_magister' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="(r, i) in sorted" :key="r.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ i + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ r.program_studi }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-blue-600">{{ r.jumlah_dosen }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-purple-600">{{ r.jumlah_guru_besar }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-green-600">{{ r.jumlah_doktor }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-amber-600">{{ r.jumlah_magister }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-amber-50 font-semibold text-sm border-t border-amber-100">
                                <td class="px-4 py-3" colspan="2">TOTAL</td>
                                <td class="px-4 py-3 text-center text-blue-700">{{ totals.dosen }}</td>
                                <td class="px-4 py-3 text-center text-purple-700">{{ totals.gb }}</td>
                                <td class="px-4 py-3 text-center text-green-700">{{ totals.doktor }}</td>
                                <td class="px-4 py-3 text-center text-amber-700">{{ totals.magister }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </PengelolaLayout>
</template>
