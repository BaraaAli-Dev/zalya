<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    order: Object,
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

const statusLabel = (status) =>
    ({
        pending: "قيد الانتظار",
        processing: "قيد التجهيز",
        delivered: "تم التوصيل",
        cancelled: "ملغي",
    })[status] ?? status;
</script>

<template>
    <Head :title="`Order #${order.id} | Zalya`" />

    <StoreLayout>
        <div class="max-w-3xl mx-auto px-4 md:px-6 py-10">
            <Link
                :href="route('orders.index')"
                class="text-sm text-brand-600 hover:text-brand-700 flex items-center gap-1 mb-4"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                العودة إلى طلباتي
            </Link>

            <div class="flex items-center gap-3 mb-8">
                <h1 class="text-2xl font-bold text-brand-900">
                    Order #{{ order.id }}
                </h1>
                <span
                    class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                    :class="statusColor(order.status)"
                >
                    {{ statusLabel(order.status) }}
                </span>
            </div>

            <div
                class="bg-white border border-brand-100 rounded-lg overflow-hidden mb-6"
            >
                <div class="px-6 py-4 border-b border-brand-100 bg-brand-50/40">
                    <h2 class="text-sm font-semibold text-brand-900">
                        المنتجات
                    </h2>
                </div>

                <div class="divide-y divide-brand-50">
                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex items-center gap-4 px-6 py-4"
                    >
                        <div
                            class="w-14 h-14 bg-brand-50/60 rounded-md overflow-hidden shrink-0"
                        >
                            <img
                                v-if="item.product?.images?.length"
                                :src="`/storage/${item.product.images[0]}`"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-brand-900">
                                {{
                                    item.product?.product_name ??
                                    "تم حذف المنتج"
                                }}
                            </p>
                            <p class="text-xs text-brand-500">
                                الكمية: {{ item.quantity }} ×
                                {{ item.price }} جنيه
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-brand-700">
                            {{ (item.price * item.quantity).toFixed(2) }} جنيه
                        </p>
                    </div>
                </div>

                <div
                    class="px-6 py-4 bg-brand-50/40 border-t border-brand-100 flex items-center justify-between"
                >
                    <span class="text-sm font-semibold text-brand-900"
                        >الإجمالي</span
                    >
                    <span class="text-lg font-bold text-brand-700"
                        >{{ order.total_price }} جنيه</span
                    >
                </div>
            </div>

            <div class="bg-white border border-brand-100 rounded-lg p-6">
                <h2 class="text-sm font-semibold text-brand-900 mb-3">
                    عنوان الشحن
                </h2>
                <p class="text-sm text-brand-700 leading-relaxed">
                    {{ order.shipping_street }}<br />
                    {{ order.shipping_city
                    }}<span v-if="order.shipping_state"
                        >, {{ order.shipping_state }}</span
                    ><br />
                    {{ order.shipping_country }}
                </p>
            </div>
        </div>
    </StoreLayout>
</template>
