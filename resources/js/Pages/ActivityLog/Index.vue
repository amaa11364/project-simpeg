<script setup>
import PengelolaLayout from '@/Layouts/PengelolaLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    logs: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const action = ref(props.filters.action || '');
let timeout;

watch([search, action], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('activity-log.index'), { search: search.value, action: action.value }, { preserveState: true, replace: true });
    }, 400);
});

const actionColors = {
    login: 'bg-green-100 text-green-700',
    logout: 'bg-gray-100 text-gray-600',
    create: 'bg-blue-100 text-blue-700',
    update: 'bg-amber-100 text-amber-700',
    delete: 'bg-red-100 text-red-600',
    change_password: 'bg-purple-100 text-purple-700',
};

const actionLabels = {
    login: 'Login',
    logout: 'Logout',
    create: 'Tambah',
    update: 'Ubah',
    delete: 'Hapus',
    change_password: 'Ganti Password',
};

const formatDate = (dt) => {
    return new Date(dt).toLocaleString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
};
</script>

<template>
    <PengelolaLayout>
        <Head title="Log Aktivitas" />
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Log Aktivitas</h1>
        </template>

        <div class="space-y-4">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input v-model="search" type="text" placeholder="Cari pengguna, deskripsi..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]" />
                </div>
                <select v-model="action"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#996600]/30 focus:border-[#996600]">
                    <option value="">Semua Aktivitas</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="create">Tambah Data</option>
                    <option value="update">Ubah Data</option>
                    <option value="delete">Hapus Data</option>
                    <option value="change_password">Ganti Password</option>
                </select>
            </div>

            <!-- Table / Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- MOBILE: Card -->
                <div class="sm:hidden divide-y divide-gray-100">
                    <div v-for="log in logs.data" :key="log.id" class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-[#996600]/10 flex items-center justify-center text-[#996600] font-bold text-xs shrink-0">
                                    {{ (log.user_name || '?').charAt(0).toUpperCase() }}
                                </div>
                                <span class="font-medium text-gray-800 text-sm">{{ log.user_name || '-' }}</span>
                            </div>
                            <span :class="actionColors[log.action] || 'bg-gray-100 text-gray-600'"
                                class="px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ actionLabels[log.action] || log.action }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700">{{ log.description }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>{{ formatDate(log.created_at) }}</span>
                            <span class="font-mono">{{ log.ip_address || '-' }}</span>
                        </div>
                    </div>
                    <div v-if="logs.data.length === 0" class="p-10 text-center text-gray-400 text-sm">
                        Belum ada log aktivitas
                    </div>
                </div>

                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                                <th class="px-4 py-3 text-left">Waktu</th>
                                <th class="px-4 py-3 text-left">Pengguna</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-left">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ formatDate(log.created_at) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-[#996600]/10 flex items-center justify-center text-[#996600] font-bold text-xs shrink-0">
                                            {{ (log.user_name || '?').charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ log.user_name || '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="actionColors[log.action] || 'bg-gray-100 text-gray-600'"
                                        class="px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ actionLabels[log.action] || log.action }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ log.description }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ log.ip_address || '-' }}</td>
                            </tr>
                            <tr v-if="logs.data.length === 0">
                                <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                                    <svg class="h-10 w-10 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Belum ada log aktivitas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                    <span>Menampilkan {{ logs.from || 0 }}-{{ logs.to || 0 }} dari {{ logs.total }} log</span>
                    <div class="flex gap-1">
                        <Link v-for="link in logs.links" :key="link.label"
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
