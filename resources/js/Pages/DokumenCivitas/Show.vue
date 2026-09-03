<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    civitas: Object,
    civitas_type: String,
    dokumen: Array,
    persen: Number,
    terisi: Number,
    total: Number,
});

const backRoute = props.civitas_type === 'dosen'
    ? route('dosen.index')
    : route('pegawai-struktural.index');

const editingKey = ref(null);

const form = useForm({
    jenis_dokumen: '',
    link: '',
    keterangan: '',
});

const startEdit = (dok) => {
    editingKey.value = dok.key;
    form.jenis_dokumen = dok.key;
    form.link = dok.link || '';
    form.keterangan = dok.keterangan || '';
};

const cancelEdit = () => {
    editingKey.value = null;
    form.reset();
};

const save = () => {
    form.post(route('dokumen.upsert', { civitasType: props.civitas_type, civitasId: props.civitas.id }), {
        onSuccess: () => {
            editingKey.value = null;
            form.reset();
        },
    });
};

const colorPersen = (p) => {
    if (p >= 80) return 'bg-green-500';
    if (p >= 50) return 'bg-amber-500';
    return 'bg-red-400';
};

const labelPersen = (p) => {
    if (p >= 80) return 'text-green-700 bg-green-50';
    if (p >= 50) return 'text-amber-700 bg-amber-50';
    return 'text-red-600 bg-red-50';
};
</script>

<template>
    <PengelolaLayout>
        <Head :title="`Dokumen - ${civitas.nama}`" />
        <template #header>
            <div class="flex items-center gap-2 text-sm">
                <Link :href="backRoute" class="text-gray-500 hover:text-gray-700">
                    {{ civitas_type === 'dosen' ? 'Data Dosen' : 'Pegawai Struktural' }}
                </Link>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-semibold">Dokumen</span>
            </div>
        </template>

        <div class="max-w-3xl space-y-5">
            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-xl bg-[#996600]/10 flex items-center justify-center text-[#996600] font-bold text-xl shrink-0 overflow-hidden border border-gray-100">
                            <img v-if="civitas.foto" :src="civitas.foto" class="h-full w-full object-cover" @error="$event.target.style.display='none'" />
                            <span v-else>{{ civitas.nama.charAt(0) }}</span>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">{{ civitas.nama }}</h2>
                            <p class="text-sm text-gray-500">
                                {{ civitas_type === 'dosen'
                                    ? (civitas.nidn_nik ? 'NIDN: ' + civitas.nidn_nik : 'Dosen')
                                    : (civitas.nomor_induk_kepegawaian ? 'NIK: ' + civitas.nomor_induk_kepegawaian : 'Pegawai Struktural')
                                }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ civitas_type === 'dosen' ? civitas.homebase_prodi : civitas.jabatan }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span :class="labelPersen(persen)" class="text-2xl font-bold px-3 py-1 rounded-lg">{{ persen }}%</span>
                        <p class="text-xs text-gray-400 mt-1">{{ terisi }} / {{ total }} dokumen</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div :class="colorPersen(persen)" class="h-full rounded-full transition-all duration-500"
                            :style="{ width: persen + '%' }"></div>
                    </div>
                </div>
            </div>

            <!-- Dokumen List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Daftar Dokumen</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Isi link dokumen (Google Drive, OneDrive, dll.)</p>
                </div>

                <div class="divide-y divide-gray-50">
                    <div v-for="dok in dokumen" :key="dok.key" class="px-5 py-4">
                        <!-- View Mode -->
                        <div v-if="editingKey !== dok.key" class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Status icon -->
                                <div :class="dok.link ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'"
                                    class="h-7 w-7 rounded-full flex items-center justify-center shrink-0">
                                    <svg v-if="dok.link" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg v-else class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800">{{ dok.label }}</p>
                                    <a v-if="dok.link" :href="dok.link" target="_blank"
                                        class="text-xs text-blue-600 hover:underline truncate block max-w-xs">
                                        {{ dok.link }}
                                    </a>
                                    <p v-else class="text-xs text-gray-400 italic">Belum ada link</p>
                                    <p v-if="dok.keterangan" class="text-xs text-gray-500 mt-0.5">{{ dok.keterangan }}</p>
                                </div>
                            </div>
                            <button @click="startEdit(dok)"
                                class="text-xs text-[#996600] hover:text-[#7a4f00] font-medium shrink-0 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ dok.link ? 'Edit' : 'Isi' }}
                            </button>
                        </div>

                        <!-- Edit Mode -->
                        <div v-else class="space-y-3">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-[#996600]/10 flex items-center justify-center shrink-0">
                                    <svg class="h-3.5 w-3.5 text-[#996600]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ dok.label }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Link Dokumen</label>
                                <input v-model="form.link" type="url" placeholder="https://drive.google.com/..."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                                <p v-if="form.errors.link" class="text-red-500 text-xs mt-1">{{ form.errors.link }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan (opsional)</label>
                                <input v-model="form.keterangan" type="text" placeholder="Misal: scan asli, berlaku s.d. 2027..."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                            </div>
                            <div class="flex gap-2">
                                <button @click="save" :disabled="form.processing"
                                    class="bg-[#996600] hover:bg-[#7a4f00] text-white text-xs font-semibold px-4 py-1.5 rounded-lg transition-colors disabled:opacity-50">
                                    Simpan
                                </button>
                                <button @click="cancelEdit"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-4 py-1.5 rounded-lg transition-colors">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PengelolaLayout>
</template>
