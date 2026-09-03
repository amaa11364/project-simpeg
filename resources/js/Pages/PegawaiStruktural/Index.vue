<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    pegawai: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const sort   = ref(props.filters.sort   || 'nama');
const dir    = ref(props.filters.dir    || 'asc');
const status = ref(props.filters.status || '');
let searchTimeout;

const navigate = () => {
    router.get(route('pegawai-struktural.index'), {
        search: search.value, sort: sort.value, dir: dir.value, status: status.value,
    }, { preserveState: true, replace: true });
};

watch([search, status], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(navigate, 400);
});

const sortBy = (col) => {
    if (sort.value === col) {
        dir.value = dir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sort.value = col;
        dir.value = 'asc';
    }
    navigate();
};

const deletePegawai = (id, nama) => {
    if (confirm(`Hapus data pegawai "${nama}"?`)) {
        router.delete(route('pegawai-struktural.destroy', id));
    }
};

const exportOpen = ref(false);

const buildExportUrl = (format) => {
    const params = new URLSearchParams();
    if (search.value) params.set('search', search.value);
    return route('export.pegawai.' + format) + (params.toString() ? '?' + params.toString() : '');
};
</script>

<template>
    <PengelolaLayout>
        <Head title="Pegawai Struktural" />
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Pegawai Struktural</h1>
        </template>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-3 flex-1 w-full">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Cari nama, NIK, jabatan..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                    </div>
                    <select v-model="status"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <!-- Export Dropdown -->
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
                    <div v-if="exportOpen"
                        class="absolute right-0 mt-1 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                        <a :href="buildExportUrl('csv')" @click="exportOpen = false"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Unduh CSV
                        </a>
                        <a :href="buildExportUrl('pdf')" @click="exportOpen = false"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 border-t border-gray-50">
                            <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>
                <Link :href="route('pegawai-struktural.create')"
                    class="flex items-center gap-2 bg-[#996600] hover:bg-[#7a4f00] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pegawai
                </Link>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- MOBILE: Card view -->
                <div class="sm:hidden divide-y divide-gray-100">
                    <div v-for="(p, i) in pegawai.data" :key="p.id" class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="h-12 w-12 rounded-xl bg-amber-50 shrink-0 overflow-hidden flex items-center justify-center border border-amber-100">
                                <img v-if="p.foto" :src="p.foto" class="h-full w-full object-cover" @error="$event.target.style.display='none'" />
                                <span v-else class="text-amber-600 font-bold text-lg">{{ p.nama.charAt(0) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 text-sm">{{ p.nama }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ p.nomor_induk_kepegawaian || 'NIK belum diisi' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 shrink-0">{{ p.pendidikan || '-' }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ p.jabatan || '-' }}</p>
                                <span :class="p.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                                    class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-semibold">{{ p.status }}</span>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2">
                                        <span :class="p.kepemilikan_rek_bri === 'Sudah Punya' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-500'"
                                            class="px-2 py-0.5 rounded-full text-xs font-medium">
                                            BRI: {{ p.kepemilikan_rek_bri === 'Sudah Punya' ? '✓' : '✗' }}
                                        </span>
                                        <Link :href="route('dokumen.show', { civitasType: 'pegawai_struktural', civitasId: p.id })"
                                            class="flex items-center gap-1">
                                            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div :class="{
                                                    'bg-green-500': p.persen_dokumen >= 80,
                                                    'bg-amber-400': p.persen_dokumen >= 50 && p.persen_dokumen < 80,
                                                    'bg-red-400': p.persen_dokumen < 50
                                                }" class="h-full rounded-full" :style="{ width: p.persen_dokumen + '%' }"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ p.persen_dokumen }}%</span>
                                        </Link>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <Link :href="route('pegawai-struktural.edit', p.id)" class="text-blue-600 text-xs font-medium">Edit</Link>
                                        <button @click="deletePegawai(p.id, p.nama)" class="text-red-500 text-xs font-medium">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="pegawai.data.length === 0" class="p-10 text-center text-gray-400 text-sm">Tidak ada data</div>
                </div>

                <!-- DESKTOP: Table view -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                                <th class="px-4 py-3 text-left w-8">#</th>
                                <th class="px-4 py-3 text-left cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('nama')">
                                    <span class="flex items-center gap-1">Nama
                                        <svg class="h-3 w-3" :class="sort==='nama' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='nama' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='nama' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-left cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('nomor_induk_kepegawaian')">
                                    <span class="flex items-center gap-1">NIK
                                        <svg class="h-3 w-3" :class="sort==='nomor_induk_kepegawaian' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='nomor_induk_kepegawaian' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='nomor_induk_kepegawaian' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-left cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('jabatan')">
                                    <span class="flex items-center gap-1">Jabatan
                                        <svg class="h-3 w-3" :class="sort==='jabatan' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='jabatan' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='jabatan' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('pendidikan')">
                                    <span class="flex items-center justify-center gap-1">Pend.
                                        <svg class="h-3 w-3" :class="sort==='pendidikan' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='pendidikan' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='pendidikan' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center cursor-pointer select-none hover:text-gray-800 group" @click="sortBy('status')">
                                    <span class="flex items-center justify-center gap-1">Status
                                        <svg class="h-3 w-3" :class="sort==='status' ? 'text-[#996600]' : 'text-gray-300 group-hover:text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path v-if="sort==='status' && dir==='asc'" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            <path v-else-if="sort==='status' && dir==='desc'" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center">Rek. BRI</th>
                                <th class="px-4 py-3 text-center">Dokumen</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="(p, i) in pegawai.data" :key="p.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ pegawai.from + i }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-amber-50 shrink-0 overflow-hidden flex items-center justify-center border border-amber-100">
                                            <img v-if="p.foto" :src="p.foto" class="h-full w-full object-cover" @error="$event.target.style.display='none'" />
                                            <span v-else class="text-amber-600 font-bold text-sm">{{ p.nama.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800">{{ p.nama }}</div>
                                            <div class="text-xs text-gray-400">{{ p.tempat_lahir }}{{ p.tanggal_lahir ? ', ' + p.tanggal_lahir : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ p.nomor_induk_kepegawaian || '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ p.jabatan || '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ p.pendidikan || '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="p.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                                        class="px-2 py-0.5 rounded-full text-xs font-semibold">{{ p.status }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="p.kepemilikan_rek_bri === 'Sudah Punya' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                                        class="px-2 py-0.5 rounded-full text-xs font-medium">
                                        {{ p.kepemilikan_rek_bri === 'Sudah Punya' ? '✓' : '✗' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <Link :href="route('dokumen.show', { civitasType: 'pegawai_struktural', civitasId: p.id })"
                                        class="flex flex-col items-center gap-1 group">
                                        <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div :class="{
                                                'bg-green-500': p.persen_dokumen >= 80,
                                                'bg-amber-400': p.persen_dokumen >= 50 && p.persen_dokumen < 80,
                                                'bg-red-400': p.persen_dokumen < 50
                                            }" class="h-full rounded-full transition-all" :style="{ width: p.persen_dokumen + '%' }"></div>
                                        </div>
                                        <span :class="{
                                            'text-green-600': p.persen_dokumen >= 80,
                                            'text-amber-600': p.persen_dokumen >= 50 && p.persen_dokumen < 80,
                                            'text-red-500': p.persen_dokumen < 50
                                        }" class="text-xs font-medium group-hover:underline">{{ p.persen_dokumen }}%</span>
                                    </Link>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <Link :href="route('pegawai-struktural.edit', p.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</Link>
                                        <button @click="deletePegawai(p.id, p.nama)" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="pegawai.data.length === 0">
                                <td colspan="9" class="px-4 py-10 text-center text-gray-400">Tidak ada data ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                    <span class="text-xs">{{ pegawai.from }}-{{ pegawai.to }} dari {{ pegawai.total }}</span>
                    <div class="flex gap-1">
                        <Link v-for="link in pegawai.links" :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                link.active ? 'bg-[#996600] text-white' : 'hover:bg-gray-100 text-gray-600',
                                !link.url ? 'opacity-40 pointer-events-none' : '',
                                'px-2.5 py-1 rounded text-xs font-medium'
                            ]"
                            v-html="link.label" />
                    </div>
                </div>
            </div>
        </div>
    </PengelolaLayout>
</template>
