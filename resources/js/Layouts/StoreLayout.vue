<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import logo from "@/assets/images/logo.png";
import CartDrawer from "@/Components/CartDrawer.vue";
import SearchModal from "@/Components/SearchModal.vue";
import { useCartDrawer } from "@/composables/useCartDrawer";
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";

const page = usePage();
const { open } = useCartDrawer();

const searchOpen = ref(false);
const accountMenuOpen = ref(false);
const notificationsOpen = ref(false);
const accountWrapper = ref(null);
const notificationsWrapper = ref(null);

const notifications = ref(page.props.notifications || []);

const unreadNotificationsCount = ref(page.props.unreadNotificationsCount || 0);

const handleClickOutside = (e) => {
    if (accountWrapper.value && !accountWrapper.value.contains(e.target)) {
        accountMenuOpen.value = false;
    }

    if (
        notificationsWrapper.value &&
        !notificationsWrapper.value.contains(e.target)
    ) {
        notificationsOpen.value = false;
    }
};

const isNotificationUnread = (notification) => !notification.read_at;

const markNotificationAsRead = async (notificationId) => {
    await axios.post(route("notifications.read", notificationId));
    await router.reload({
        only: ["notifications", "unreadNotificationsCount"],
    });
    notificationsOpen.value = false;
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    notifications.value = page.props.notifications || [];
    unreadNotificationsCount.value = page.props.unreadNotificationsCount || 0;
});

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

                    <div
                        v-if="page.props.auth?.user"
                        class="relative"
                        ref="notificationsWrapper"
                    >
                        <button
                            type="button"
                            @click.stop="notificationsOpen = !notificationsOpen"
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
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"
                                />
                            </svg>
                            <span
                                v-if="
                                    (page.props.unreadNotificationsCount || 0) >
                                    0
                                "
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
                            >
                                {{
                                    (page.props.unreadNotificationsCount || 0) >
                                    9
                                        ? "9+"
                                        : page.props.unreadNotificationsCount
                                }}
                            </span>
                        </button>

                        <div
                            v-show="notificationsOpen"
                            class="absolute right-0 mt-2 w-80 bg-white border border-brand-100 rounded-xl shadow-xl z-50 overflow-hidden"
                        >
                            <div
                                class="flex items-center justify-between px-4 py-3 border-b border-brand-100"
                            >
                                <h3
                                    class="text-sm font-semibold text-brand-900"
                                >
                                    Notifications
                                </h3>
                            </div>

                            <div
                                v-if="(page.props.notifications || []).length"
                                class="max-h-80 overflow-y-auto"
                            >
                                <button
                                    v-for="notification in page.props
                                        .notifications"
                                    :key="notification.id"
                                    type="button"
                                    @click="
                                        markNotificationAsRead(notification.id)
                                    "
                                    :class="[
                                        isNotificationUnread(notification)
                                            ? 'bg-brand-50 border-l-4 border-brand-700'
                                            : 'bg-white opacity-80 border-l-4 border-transparent',
                                        'block w-full text-left px-4 py-3 border-b border-brand-50 last:border-0 transition-colors hover:bg-brand-50',
                                    ]"
                                >
                                    <div
                                        class="flex items-start justify-between gap-3"
                                    >
                                        <div>
                                            <p
                                                :class="[
                                                    isNotificationUnread(
                                                        notification,
                                                    )
                                                        ? 'text-brand-900 font-semibold'
                                                        : 'text-brand-600',
                                                    'text-sm',
                                                ]"
                                            >
                                                {{
                                                    notification.data.message ||
                                                    "New notification"
                                                }}
                                            </p>
                                            <p
                                                class="mt-1 text-[11px] text-brand-500"
                                            >
                                                {{
                                                    new Date(
                                                        notification.created_at,
                                                    ).toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                        <span
                                            v-if="
                                                isNotificationUnread(
                                                    notification,
                                                )
                                            "
                                            class="mt-1 h-2.5 w-2.5 rounded-full bg-brand-700 flex-shrink-0"
                                        ></span>
                                    </div>
                                </button>
                            </div>

                            <div
                                v-else
                                class="px-4 py-6 text-sm text-brand-500 text-center"
                            >
                                No notifications yet.
                            </div>
                        </div>
                    </div>
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
