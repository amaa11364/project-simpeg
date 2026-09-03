<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="SIMPEG - Sistem Informasi Manajemen Kepegawaian">
        <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico" />
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png" />
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="192x192" href="/assets/img/android-chrome-192x192.png" />
        <link rel="icon" type="image/png" sizes="512x512" href="/assets/img/android-chrome-512x512.png" />
    </Head>

    <div class="relative min-h-screen bg-[#F5F1E8] overflow-hidden">
        <!-- Decorative background circles -->
        <div class="absolute top-10 left-20 w-32 h-32 rounded-full bg-[#EBE3D5] opacity-50"></div>
        <div class="absolute top-1/4 left-0 w-48 h-48 rounded-full bg-[#EBE3D5] opacity-40"></div>
        <div class="absolute top-1/2 right-0 w-64 h-64 rounded-full bg-[#EBE3D5] opacity-40"></div>
        <div class="absolute bottom-20 left-10 w-40 h-40 rounded-full bg-[#EBE3D5] opacity-30"></div>
        <div class="absolute bottom-32 right-32 w-56 h-56 rounded-full bg-[#EBE3D5] opacity-30"></div>

        <!-- Header -->
        <header class="relative z-10 flex items-center justify-between px-8 py-6">
            <div class="flex items-center gap-3">
                <img src="/assets/img/logo.png" alt="Logo" class="h-10 w-10 rounded-full" />
                <span class="text-2xl font-bold text-gray-800">SIMPEG</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex flex-col items-center justify-center px-6 py-16">
            <!-- Institution Logo -->
            <div class="mb-8">
                <img
                    src="/assets/img/logo-996600.png"
                    alt="Logo IKIP Siliwangi"
                    class="h-32 w-32 object-contain"
                />
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-center mb-3" style="color: #B8860B;">
                Sistem Informasi Manajemen Kepegawaian
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-gray-700 text-center mb-12">
                Bagian Kepegawaian IKIP Siliwangi
            </p>
        </main>

        <!-- Footer -->
        <footer class="absolute bottom-0 w-full py-6 text-center text-white text-sm" style="background-color: #B8860B;">
            Bagian Kepegawaian dan Bagian Pengembangan IT IKIP Siliwangi © 2020-2025
        </footer>
    </div>
</template>
