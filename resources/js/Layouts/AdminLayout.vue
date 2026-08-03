<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const links = [
    { label: "Home", route: "admin.dashboard" },
    { label: "Products", route: "#" },
    { label: "Categories", route: "#" },
    { label: "Orders", route: "#" },
];

const isActive = (routeName) => {
    return routeName !== "#" && route().current(routeName);
};
</script>

<template>
    <div class="flex min-h-screen bg-brand-50/20">
        <!-- Sidebar -->
        <aside class="w-64 bg-brand-900 text-white p-6 flex flex-col">
            <h2 class="text-xl font-bold mb-10 text-brand-50">
                Zalya Control Panel
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
        <div class="flex-1 flex flex-col">
            <header
                class="bg-white shadow-sm border-b border-brand-100 p-4 flex justify-between items-center"
            >
                <h1 class="text-lg font-semibold text-brand-700">
                    Hello, {{ page.props.auth.user.name }}!
                </h1>

                <Link
                    :href="route('admin.logout')"
                    method="post"
                    as="button"
                    class="text-sm text-red-600 hover:text-red-700 transition-colors duration-200 font-medium"
                >
                    Logout
                </Link>
            </header>

            <main class="p-6 flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
