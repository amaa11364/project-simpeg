<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gradient-to-br from-[#7a4f00] via-[#996600] to-[#b37d00] pt-6 sm:justify-center sm:pt-0"
    >
        <div class="mb-6 flex flex-col items-center">
            <Link href="/">
                <img
                    src="/images/ikip/logo-white.png"
                    alt="IKIP Siliwangi"
                    class="h-24 w-auto"
                />
            </Link>
            <h1 class="mt-3 text-xl font-bold text-white tracking-wide">IKIP Siliwangi</h1>
            <p class="text-sm text-amber-200">Sistem Informasi Kepegawaian</p>
        </div>

        <div
            class="w-full overflow-hidden bg-white px-6 py-6 shadow-xl sm:max-w-md sm:rounded-xl"
        >
            <slot />
        </div>

        <p class="mt-6 text-xs text-amber-200">&copy; {{ new Date().getFullYear() }} IKIP Siliwangi. All rights reserved.</p>
    </div>
</template>
