<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ pegawai: Object, dokumen: Array, persen: Number });
const isEdit = !!props.pegawai;

const form = useForm({
    nomor_induk_kepegawaian: props.pegawai?.nomor_induk_kepegawaian || '',
    nama: props.pegawai?.nama || '',
    foto: props.pegawai?.foto || '',
    foto_file: null,
    tempat_lahir: props.pegawai?.tempat_lahir || '',
    tanggal_lahir: props.pegawai?.tanggal_lahir || '',
    pendidikan: props.pegawai?.pendidikan || '',
    jabatan: props.pegawai?.jabatan || '',
    alamat: props.pegawai?.alamat || '',
    nik_ktp: props.pegawai?.nik_ktp || '',
    kepemilikan_rek_bri: props.pegawai?.kepemilikan_rek_bri || '',
    status: props.pegawai?.status || 'Aktif',
});

const fotoMode = ref(props.pegawai?.foto && props.pegawai.foto.startsWith('/storage/') ? 'upload' : 'link');
const fotoPreview = ref(null);

const fotoSrc = computed(() => {
    if (fotoPreview.value) return fotoPreview.value;
    if (fotoMode.value === 'link') return form.foto || null;
    return props.pegawai?.foto || null;
});

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    form.foto_file = file;
    fotoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    if (isEdit) {
        form.transform((data) => ({
            ...data,
            _method: 'patch',
        })).post(route('pegawai-struktural.update', props.pegawai.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('pegawai-struktural.store'), { forceFormData: true });
    }
};
</script>

<template>
    <PengelolaLayout>
        <Head :title="isEdit ? 'Edit Pegawai' : 'Tambah Pegawai'" />
        <template #header>
            <div class="flex items-center gap-2 text-sm">
                <Link :href="route('pegawai-struktural.index')" class="text-gray-500 hover:text-gray-700">Pegawai Struktural</Link>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-semibold">{{ isEdit ? 'Edit' : 'Tambah' }}</span>
            </div>
        </template>

        <div class="max-w-2xl space-y-4">
            <!-- Panel Dokumen (hanya saat edit) -->
            <div v-if="isEdit" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Kelengkapan Dokumen</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ dokumen?.filter(d => d.link).length || 0 }} / {{ dokumen?.length || 0 }} dokumen terisi</p>
                    </div>
                    <Link :href="route('dokumen.show', { civitasType: 'pegawai_struktural', civitasId: pegawai.id })"
                        class="flex items-center gap-2 bg-[#996600] hover:bg-[#7a4f00] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Kelola Dokumen
                    </Link>
                </div>
                <div class="px-5 pb-3">
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div :class="{
                            'bg-green-500': persen >= 80,
                            'bg-amber-400': persen >= 50 && persen < 80,
                            'bg-red-400': persen < 50
                        }" class="h-full rounded-full transition-all" :style="{ width: persen + '%' }"></div>
                    </div>
                    <p :class="{
                        'text-green-600': persen >= 80,
                        'text-amber-600': persen >= 50 && persen < 80,
                        'text-red-500': persen < 50
                    }" class="text-xs font-semibold mt-1">{{ persen }}% lengkap</p>
                </div>
                <div class="px-5 pb-4 grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                    <div v-for="dok in dokumen" :key="dok.key" class="flex items-center gap-1.5 text-xs">
                        <span :class="dok.link ? 'text-green-500' : 'text-gray-300'">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path v-if="dok.link" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                        <span :class="dok.link ? 'text-gray-700' : 'text-gray-400'">{{ dok.label }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-6">{{ isEdit ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru' }}</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Foto (upload atau link) -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                            <div class="flex gap-3 items-start">
                                <div class="h-16 w-16 rounded-xl bg-gray-100 shrink-0 overflow-hidden flex items-center justify-center border border-gray-200">
                                    <img v-if="fotoSrc" :src="fotoSrc" class="h-full w-full object-cover" @error="$event.target.style.display='none'" />
                                    <svg v-else class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1 space-y-2">
                                    <div class="flex gap-2">
                                        <button type="button" @click="fotoMode = 'upload'"
                                            :class="fotoMode === 'upload' ? 'bg-[#996600] text-white' : 'bg-gray-100 text-gray-600'"
                                            class="text-xs px-3 py-1 rounded-lg font-medium transition-colors">Upload File</button>
                                        <button type="button" @click="fotoMode = 'link'"
                                            :class="fotoMode === 'link' ? 'bg-[#996600] text-white' : 'bg-gray-100 text-gray-600'"
                                            class="text-xs px-3 py-1 rounded-lg font-medium transition-colors">Link URL</button>
                                    </div>
                                    <div v-if="fotoMode === 'upload'">
                                        <input type="file" accept="image/*" @change="onFileChange"
                                            class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#996600]/10 file:text-[#996600] hover:file:bg-[#996600]/20 cursor-pointer" />
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF — maks. 2 MB</p>
                                    </div>
                                    <div v-else>
                                        <input v-model="form.foto" type="url" placeholder="https://drive.google.com/... atau link foto langsung"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                                        <p class="text-xs text-gray-400 mt-1">Link URL foto langsung (bukan halaman preview)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kepegawaian</label>
                            <input v-model="form.nomor_induk_kepegawaian" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                            <input v-model="form.nama" type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                            <p v-if="form.errors.nama" class="text-red-500 text-xs mt-1">{{ form.errors.nama }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input v-model="form.tempat_lahir" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input v-model="form.tanggal_lahir" type="text" placeholder="contoh: 28 Februari 1940" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                            <select v-model="form.pendidikan" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]">
                                <option value="">Pilih...</option>
                                <option>S3</option><option>S2</option><option>S1</option>
                                <option>D3</option><option>SMA</option><option>SMK</option>
                                <option>STM</option><option>SMP</option><option>SD</option>
                                <option>Paket C</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input v-model="form.jabatan" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK KTP</label>
                            <input v-model="form.nik_ktp" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kepemilikan Rek. BRI</label>
                            <select v-model="form.kepemilikan_rek_bri" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]">
                                <option value="">Pilih...</option>
                                <option>Sudah Punya</option>
                                <option>Belum</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepegawaian</label>
                            <select v-model="form.status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]">
                                <option>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea v-model="form.alamat" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="bg-[#996600] hover:bg-[#7a4f00] text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors disabled:opacity-50">
                            {{ isEdit ? 'Simpan Perubahan' : 'Tambah Pegawai' }}
                        </button>
                        <Link :href="route('pegawai-struktural.index')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition-colors">Batal</Link>
                    </div>
                </form>
            </div>
        </div>
    </PengelolaLayout>
</template>
