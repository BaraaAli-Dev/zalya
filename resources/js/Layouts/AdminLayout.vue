<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";
import logo from "@/assets/images/logo.png";
import FlashMessage from "@/Components/FlashMessage.vue";

const page = usePage();
const notifications = ref([]);
const unreadCount = ref(0);
const locale = ref(usePage().props.locale || "ar");
const notificationsOpen = ref(false);
const notificationsWrapper = ref(null);

const switchLocale = () => {
    window.location.href = route(
        "language.switch",
        locale.value === "ar" ? "en" : "ar",
    );
};

const links = [
    { label: "لوحة التحكم", route: "admin.dashboard" },
    { label: "التصنيفات", route: "admin.categories.index" },
    { label: "المنتجات", route: "admin.products.index" },
    { label: "الطلبات", route: "admin.orders.index" },
];

const isActive = (routeName) => route().current(routeName);

const fetchNotifications = async () => {
    if (!route().has("admin.notifications.index")) {
        return;
    }

    const { data } = await axios.get(route("admin.notifications.index"));
    notifications.value = data.notifications;
    unreadCount.value = data.unread_count;
};

const getNotificationMessage = (notification) => {
    return notification?.data?.message || "إشعار جديد";
};

const getNotificationOrderId = (notification) => {
    return notification?.data?.order_id;
};

const isNotificationUnread = (notification) => !notification.read_at;

const markAsRead = async (id) => {
    await axios.post(route("admin.notifications.read", id));
    await fetchNotifications();
};

const markAllAsRead = async () => {
    await axios.post(route("admin.notifications.readAll"));
    await fetchNotifications();
};

const handleClickOutside = (event) => {
    if (
        notificationsWrapper.value &&
        !notificationsWrapper.value.contains(event.target)
    ) {
        notificationsOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    fetchNotifications();
    setInterval(fetchNotifications, 30000);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
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
                لوحة تحكم Zalya
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
                    مرحبًا، {{ page.props.auth.admin?.name ?? "المسؤول" }}!
                </h1>

                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        @click="switchLocale"
                        class="text-xs font-semibold text-brand-700 hover:text-brand-900 transition-colors"
                        :aria-label="
                            locale === 'ar'
                                ? 'Switch to English'
                                : 'التبديل إلى العربية'
                        "
                    >
                        {{ locale === "ar" ? "English" : "العربية" }}
                    </button>
                    <div class="relative" ref="notificationsWrapper">
                        <button
                            type="button"
                            @click.stop="notificationsOpen = !notificationsOpen"
                            class="relative inline-flex items-center justify-center rounded-full p-2 text-brand-700 hover:bg-brand-50 transition-colors"
                            aria-label="Toggle notifications"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
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
                                v-if="unreadCount > 0"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full h-4 min-w-4 px-1 flex items-center justify-center"
                            >
                                {{ unreadCount > 9 ? "9+" : unreadCount }}
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
                                    الإشعارات
                                </h3>
                                <button
                                    v-if="unreadCount > 0"
                                    type="button"
                                    @click="markAllAsRead"
                                    class="text-xs font-medium text-brand-700 hover:text-brand-900"
                                >
                                    تعليم الكل كمقروء
                                </button>
                            </div>

                            <div
                                v-if="notifications.length"
                                class="max-h-80 overflow-y-auto"
                            >
                                <div
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    class="border-b border-brand-50 last:border-0"
                                >
                                    <Link
                                        v-if="
                                            getNotificationOrderId(notification)
                                        "
                                        :href="
                                            route(
                                                'admin.orders.show',
                                                getNotificationOrderId(
                                                    notification,
                                                ),
                                            )
                                        "
                                        :class="[
                                            isNotificationUnread(notification)
                                                ? 'bg-brand-50 border-l-4 border-brand-700'
                                                : 'bg-white opacity-80 border-l-4 border-transparent',
                                            'block px-4 py-3 transition-colors hover:bg-brand-50',
                                        ]"
                                        @click="markAsRead(notification.id)"
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
                                                        getNotificationMessage(
                                                            notification,
                                                        )
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
                                    </Link>
                                    <button
                                        v-else
                                        type="button"
                                        @click="markAsRead(notification.id)"
                                        :class="[
                                            isNotificationUnread(notification)
                                                ? 'bg-brand-50 border-l-4 border-brand-700'
                                                : 'bg-white opacity-80 border-l-4 border-transparent',
                                            'block w-full text-left px-4 py-3 transition-colors hover:bg-brand-50',
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
                                                        getNotificationMessage(
                                                            notification,
                                                        )
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
                            </div>

                            <div
                                v-else
                                class="px-4 py-6 text-sm text-brand-500 text-center"
                            >
                                No notifications yet.
                            </div>
                        </div>
                    </div>

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
