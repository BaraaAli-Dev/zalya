<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    orders: Object,
});

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
    <Head title="My Orders | Zalya" />

    <StoreLayout>
        <div class="max-w-4xl mx-auto px-4 md:px-6 py-10">
            <h1 class="text-2xl font-bold text-brand-900 mb-8">My Orders</h1>

            <div v-if="orders.data.length === 0" class="text-center py-24">
                <p class="text-brand-600 mb-6">
                    You haven't placed any orders yet.
                </p>
                <Link
                    :href="route('home')"
                    class="inline-block bg-brand-700 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors"
                >
                    Start Shopping
                </Link>
            </div>

            <div v-else class="space-y-4">
                <Link
                    v-for="order in orders.data"
                    :key="order.id"
                    :href="route('orders.show', order.id)"
                    class="block bg-white border border-brand-100 rounded-lg p-5 hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-brand-900">
                            Order #{{ order.id }}
                        </p>
                        <span
                            class="px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                            :class="statusColor(order.status)"
                        >
                            {{ order.status }}
                        </span>
                    </div>
                    <div
                        class="flex items-center justify-between text-sm text-brand-600"
                    >
                        <span>{{
                            new Date(order.created_at).toLocaleDateString(
                                "en-US",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                },
                            )
                        }}</span>
                        <span class="font-semibold text-brand-900"
                            >{{ order.total_price }} EGP</span
                        >
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div
                class="mt-8 flex justify-center gap-1"
                v-if="orders.links.length > 3"
            >
                <Link
                    v-for="(link, index) in orders.links"
                    :key="index"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-3 py-1.5 rounded-md text-sm transition-colors duration-150"
                    :class="[
                        link.active
                            ? 'bg-brand-700 text-white'
                            : 'text-gray-600 hover:bg-brand-50',
                        !link.url && 'opacity-40 cursor-not-allowed',
                    ]"
                />
            </div>
        </div>
    </StoreLayout>
</template>
