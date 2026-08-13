<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

defineProps({
    recentOrders: Array,
    stats: Object,
});

const page = usePage();

const statusColor = (status) => {
    return (
        {
            pending: "bg-yellow-50 text-yellow-700",
            processing: "bg-blue-50 text-blue-700",
            delivered: "bg-green-50 text-green-700",
            cancelled: "bg-red-50 text-red-700",
        }[status] ?? "bg-gray-50 text-gray-700"
    );
};
</script>

<template>
    <Head title="My Account | Zalya" />

    <StoreLayout>
        <div class="max-w-4xl mx-auto px-4 md:px-6 py-10">
            <h1 class="text-2xl font-bold text-brand-900 mb-1">
                Welcome back, {{ page.props.auth.user.name }} 👋
            </h1>
            <p class="text-sm text-brand-600 mb-8">
                Here's a quick overview of your account.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-white border border-brand-100 rounded-lg p-5">
                    <p class="text-xs text-brand-500 mb-1">Total Orders</p>
                    <p class="text-2xl font-bold text-brand-900">
                        {{ stats.total }}
                    </p>
                </div>
                <div class="bg-white border border-brand-100 rounded-lg p-5">
                    <p class="text-xs text-brand-500 mb-1">Active Orders</p>
                    <p class="text-2xl font-bold text-brand-900">
                        {{ stats.pending }}
                    </p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <Link
                    :href="route('orders.index')"
                    class="bg-brand-700 text-white rounded-lg p-5 hover:bg-brand-800 transition-colors flex items-center justify-between"
                >
                    <span class="text-sm font-medium">My Orders</span>
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
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </Link>
                <Link
                    :href="route('profile.edit')"
                    class="bg-white border border-brand-100 text-brand-900 rounded-lg p-5 hover:bg-brand-50/50 transition-colors flex items-center justify-between"
                >
                    <span class="text-sm font-medium">My Profile</span>
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
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </Link>
            </div>

            <!-- Recent Orders -->
            <div
                class="bg-white border border-brand-100 rounded-lg overflow-hidden"
            >
                <div
                    class="px-6 py-4 border-b border-brand-100 bg-brand-50/40 flex items-center justify-between"
                >
                    <h2 class="text-sm font-semibold text-brand-900">
                        Recent Orders
                    </h2>
                    <Link
                        v-if="recentOrders.length > 0"
                        :href="route('orders.index')"
                        class="text-xs text-brand-700 hover:underline font-medium"
                    >
                        View All
                    </Link>
                </div>

                <div
                    v-if="recentOrders.length === 0"
                    class="px-6 py-10 text-center text-brand-500 text-sm"
                >
                    You haven't placed any orders yet.
                    <Link
                        :href="route('home')"
                        class="text-brand-700 hover:underline block mt-2"
                    >
                        Start Shopping
                    </Link>
                </div>

                <div v-else class="divide-y divide-brand-50">
                    <Link
                        v-for="order in recentOrders"
                        :key="order.id"
                        :href="route('orders.show', order.id)"
                        class="flex items-center justify-between px-6 py-4 hover:bg-brand-50/30 transition-colors"
                    >
                        <div>
                            <p class="text-sm font-medium text-brand-900">
                                Order #{{ order.id }}
                            </p>
                            <p class="text-xs text-brand-500 mt-0.5">
                                {{
                                    new Date(
                                        order.created_at,
                                    ).toLocaleDateString("en-US", {
                                        year: "numeric",
                                        month: "long",
                                        day: "numeric",
                                    })
                                }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                                :class="statusColor(order.status)"
                            >
                                {{ order.status }}
                            </span>
                            <span class="text-sm font-semibold text-brand-900"
                                >{{ order.total_price }} EGP</span
                            >
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
