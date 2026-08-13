<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import logo from "@/assets/images/logo.png";
import CartDrawer from "@/Components/CartDrawer.vue";
import SearchModal from "@/Components/SearchModal.vue";
import { useCartDrawer } from "@/composables/useCartDrawer";
import { ref, onMounted, onUnmounted } from "vue";

const page = usePage();
const { open } = useCartDrawer();

const searchOpen = ref(false);
const accountMenuOpen = ref(false);
const accountWrapper = ref(null);

const handleClickOutside = (e) => {
    if (accountWrapper.value && !accountWrapper.value.contains(e.target)) {
        accountMenuOpen.value = false;
    }
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-brand-50/30">
        <!-- Navbar -->
        <header class="border-b border-brand-100 sticky top-0 bg-white z-50">
            <div
                class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between"
            >
                <Link :href="route('home')" class="flex items-center gap-2">
                    <img :src="logo" alt="Zalya" class="h-10 w-auto" />
                </Link>

                <div class="flex items-center gap-5">
                    <div class="relative" ref="searchWrapper">
                        <button
                            @click="searchOpen = !searchOpen"
                            class="text-brand-900 hover:text-brand-700 transition-colors"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </button>

                        <SearchModal
                            :is-open="searchOpen"
                            @close="searchOpen = false"
                        />
                    </div>
                    <button
                        @click="open"
                        class="relative text-brand-900 hover:text-brand-700 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        <span
                            v-if="page.props.cartCount > 0"
                            class="absolute -top-2 -right-2 bg-brand-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
                        >
                            {{ page.props.cartCount }}
                        </span>
                    </button>
                    <!-- Auth -->
                    <Link
                        v-if="page.props.auth?.admin"
                        :href="route('admin.dashboard')"
                        class="text-sm font-medium text-brand-700 bg-brand-50 px-3 py-1.5 rounded-md hover:bg-brand-100 transition-colors"
                    >
                        Admin Dashboard
                    </Link>
                    <Link
                        v-if="!page.props.auth?.user"
                        :href="route('login')"
                        class="text-sm font-medium text-brand-900 hover:text-brand-700 transition-colors"
                    >
                        Login
                    </Link>

                    <div v-else class="relative" ref="accountWrapper">
                        <button
                            @click="accountMenuOpen = !accountMenuOpen"
                            class="text-sm font-medium text-brand-900 hover:text-brand-700 transition-colors flex items-center gap-1"
                        >
                            {{ page.props.auth.user.name }}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        <div
                            v-show="accountMenuOpen"
                            class="absolute right-0 mt-2 w-44 bg-white border border-brand-100 rounded-md shadow-lg py-1 z-50"
                        >
                            <Link
                                :href="route('dashboard')"
                                class="block px-4 py-2 text-sm text-brand-900 hover:bg-brand-50 transition-colors"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('orders.index')"
                                class="block px-4 py-2 text-sm text-brand-900 hover:bg-brand-50 transition-colors"
                            >
                                My Orders
                            </Link>
                            <Link
                                :href="route('profile.edit')"
                                class="block px-4 py-2 text-sm text-brand-900 hover:bg-brand-50 transition-colors"
                            >
                                My Profile
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                            >
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="bg-brand-900 text-brand-50 py-10 mt-16">
            <div class="max-w-7xl mx-auto px-6 text-center text-sm">
                <img
                    :src="logo"
                    alt="Zalya"
                    class="h-9 w-auto mx-auto mb-4 opacity-90"
                />
                <p class="text-brand-100">
                    © {{ new Date().getFullYear() }} Zalya — Unforgettable
                    Fragrance
                </p>
            </div>
        </footer>
    </div>
    <CartDrawer />
</template>
