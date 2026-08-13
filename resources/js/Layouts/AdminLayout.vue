<script setup>
import FlashMessage from "@/Components/FlashMessage.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import logo from "@/assets/images/logo.png";

const page = usePage();

const links = [
    { label: "Home", route: "admin.dashboard" },
    { label: "Products", route: "admin.products.index" },
    { label: "Categories", route: "admin.categories.index" },
    { label: "Orders", route: "admin.orders.index" },
    { label: "View Store", route: "home" },
];

const isActive = (routeName) => {
    return routeName !== "#" && route().current(routeName);
};
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-brand-50/20">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-brand-900 text-white p-6 flex flex-col overflow-y-auto"
        >
            <h2 class="text-xl font-bold mb-10 text-brand-50">
                <img
                    :src="logo"
                    alt="Zalya Logo"
                    class="h-10 w-10 inline-block mr-2"
                />
                Zalya Dashboard
            </h2>

            <nav class="space-y-1 flex-1">
                <Link
                    v-for="link in links"
                    :key="link.label"
                    :href="link.route === '#' ? '#' : route(link.route)"
                    class="block px-4 py-2.5 rounded-md text-sm transition-all duration-200 ease-in-out"
                    :class="
                        isActive(link.route)
                            ? 'bg-brand-700 text-white font-medium'
                            : 'text-brand-100 hover:bg-brand-800 hover:text-white'
                    "
                >
                    {{ link.label }}
                </Link>
            </nav>
            <div class="pt-6 border-t border-brand-800 text-xs text-brand-100">
                © {{ new Date().getFullYear() }} Zalya
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="bg-white shadow-sm border-b border-brand-100 p-4 flex justify-between items-center flex-shrink-0"
            >
                <h1 class="text-lg font-semibold text-brand-700">
                    <img
                        :src="logo"
                        alt="Zalya Logo"
                        class="h-10 w-10 inline-block mr-2"
                    />
                    Hello, {{ page.props.auth.user.name }}!
                </h1>

                <div class="flex items-center gap-4">
                    <a
                        :href="route('home')"
                        class="text-sm font-medium text-brand-700 bg-brand-50 px-3 py-1.5 rounded-md hover:bg-brand-100 transition-colors flex items-center gap-1.5"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                            />
                        </svg>
                        View Store
                    </a>

                    <Link
                        :href="route('admin.logout')"
                        method="post"
                        as="button"
                        class="text-sm text-red-600 hover:text-red-700 transition-colors duration-200 font-medium"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <main class="p-6 flex-1 overflow-y-auto">
                <FlashMessage />
                <slot />
            </main>
        </div>
    </div>
</template>
