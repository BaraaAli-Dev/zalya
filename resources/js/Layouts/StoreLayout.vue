<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import logo from '@/assets/images/logo.png';

const page = usePage();
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- Navbar -->
        <header class="border-b border-brand-100 sticky top-0 bg-white z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <Link :href="route('home')" class="flex items-center gap-2">
                    <img :src="logo" alt="Zalya" class="h-10 w-auto" />
                </Link>

                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-brand-900">
                    <Link :href="route('home')" class="hover:text-brand-700 transition-colors">Home</Link>
                    <Link href="#" class="hover:text-brand-700 transition-colors">Men</Link>
                    <Link href="#" class="hover:text-brand-700 transition-colors">Women</Link>
                    <Link href="#" class="hover:text-brand-700 transition-colors">Unisex</Link>
                </nav>

                <div class="flex items-center gap-5">
                    <!-- Search -->
                    <button class="text-brand-900 hover:text-brand-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Cart -->
                    <Link href="#" class="relative text-brand-900 hover:text-brand-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-brand-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                            0
                        </span>
                    </Link>

                    <!-- Auth -->
                    <Link
                        v-if="!page.props.auth?.user"
                        :href="route('login')"
                        class="text-sm font-medium text-brand-900 hover:text-brand-700 transition-colors"
                    >
                        Login
                    </Link>
                    <Link
                        v-else
                        :href="route('profile.edit')"
                        class="text-sm font-medium text-brand-900 hover:text-brand-700 transition-colors"
                    >
                        My Account
                    </Link>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-brand-900 text-brand-50 py-10 mt-20">
            <div class="max-w-7xl mx-auto px-6 text-center text-sm">
                <img :src="logo" alt="Zalya" class="h-10 w-auto mx-auto mb-4 opacity-90" />
                <p class="text-brand-100">© {{ new Date().getFullYear() }} Zalya — Unforgettable Fragrance</p>
            </div>
        </footer>
    </div>
</template>
