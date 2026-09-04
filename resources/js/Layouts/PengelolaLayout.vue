<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
const page = usePage();

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Data Dosen', href: route('dosen.index'), icon: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z' },
    { name: 'Pegawai Struktural', href: route('pegawai-struktural.index'), icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Rekap Prodi', href: route('rekap-prodi.index'), icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { name: 'Log Aktivitas', href: route('activity-log.index'), icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
    { name: 'Ganti Password', href: route('ganti-password'), icon: 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z' },
];

const isActive = (href) => {
    return page.url.startsWith(new URL(href).pathname);
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar Mobile Overlay -->
        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', 'fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0']"
            class="flex flex-col bg-gradient-to-b from-[#7a4f00] to-[#996600] shadow-xl">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/20">
                <img src="/images/ikip/logo-white.png" alt="IKIP" class="h-10 w-auto" onerror="this.style.display='none'" />
                <div>
                    <div class="text-white font-bold text-sm leading-tight">IKIP Siliwangi</div>
                    <div class="text-amber-200 text-xs">Sistem Kepegawaian</div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        isActive(item.href)
                            ? 'bg-white/20 text-white font-semibold'
                            : 'text-amber-100 hover:bg-white/10 hover:text-white',
                        'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors'
                    ]"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>
                    {{ item.name }}
                </Link>
            </nav>

            <!-- User Info -->
            <div class="border-t border-white/20 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-white/30 flex items-center justify-center text-white font-bold text-sm">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ $page.props.auth.user.name }}</div>
                        <div class="text-amber-200 text-xs truncate">{{ $page.props.auth.user.role }}</div>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="text-amber-200 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-14 px-4 sm:px-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div v-if="$slots.header" class="flex-1 lg:ml-0 ml-3">
                        <slot name="header" />
                    </div>
                    <div class="hidden lg:flex items-center gap-2 text-sm text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="mx-4 sm:mx-6 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                    <svg class="h-4 w-4 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $page.props.flash.success }}
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
