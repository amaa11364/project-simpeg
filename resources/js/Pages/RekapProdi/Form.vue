<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({ rekap: Object });
const isEdit = !!props.rekap;

const form = useForm({
    program_studi: props.rekap?.program_studi || '',
    jumlah_dosen: props.rekap?.jumlah_dosen || 0,
    jumlah_guru_besar: props.rekap?.jumlah_guru_besar || 0,
    jumlah_doktor: props.rekap?.jumlah_doktor || 0,
    jumlah_magister: props.rekap?.jumlah_magister || 0,
});

const submit = () => {
    if (isEdit) {
        form.patch(route('rekap-prodi.update', props.rekap.id));
    } else {
        form.post(route('rekap-prodi.store'));
    }
};
</script>

<template>
    <PengelolaLayout>
        <Head :title="isEdit ? 'Edit Rekap Prodi' : 'Tambah Rekap Prodi'" />
        <template #header>
            <div class="flex items-center gap-2 text-sm">
                <Link :href="route('rekap-prodi.index')" class="text-gray-500 hover:text-gray-700">Rekap Prodi</Link>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-semibold">{{ isEdit ? 'Edit' : 'Tambah' }}</span>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-6">{{ isEdit ? 'Edit Rekap Prodi' : 'Tambah Rekap Prodi' }}</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                        <input v-model="form.program_studi" type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        <p v-if="form.errors.program_studi" class="text-red-500 text-xs mt-1">{{ form.errors.program_studi }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dosen</label>
                            <input v-model.number="form.jumlah_dosen" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guru Besar</label>
                            <input v-model.number="form.jumlah_guru_besar" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Doktor (S3)</label>
                            <input v-model.number="form.jumlah_doktor" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Magister (S2)</label>
                            <input v-model.number="form.jumlah_magister" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="bg-[#996600] hover:bg-[#7a4f00] text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors disabled:opacity-50">
                            {{ isEdit ? 'Simpan Perubahan' : 'Tambah Rekap' }}
                        </button>
                        <Link :href="route('rekap-prodi.index')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition-colors">Batal</Link>
                    </div>
                </form>
            </div>
        </div>
    </PengelolaLayout>
</template>
