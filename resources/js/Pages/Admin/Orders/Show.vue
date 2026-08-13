<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";

defineOptions({ layout: AdminLayout });

const props = defineProps({
    order: Object,
});

const form = useForm({
    status: props.order.status,
});

const updateStatus = () => {
    form.patch(route("admin.orders.updateStatus", props.order.id), {
        preserveScroll: true,
    });
};

const deleteOrder = () => {
    if (
        confirm(
            `Are you sure you want to delete Order #${props.order.id}? This action cannot be undone.`,
        )
    ) {
        router.delete(route("admin.orders.destroy", props.order.id));
    }
};

const statusColor = (status) => {
    return (
        {
            pending: "bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200",
            processing: "bg-blue-50 text-blue-700 ring-1 ring-blue-200",
            delivered: "bg-green-50 text-green-700 ring-1 ring-green-200",
            cancelled: "bg-red-50 text-red-700 ring-1 ring-red-200",
        }[status] ?? "bg-gray-50 text-gray-700"
    );
};
</script>

<template>
    <div class="max-w-5xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <Link
                    :href="route('admin.orders.index')"
                    class="text-sm text-brand-600 hover:text-brand-700 flex items-center gap-1 mb-2 transition-colors"
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
                    Back to Orders
                </Link>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-brand-900">
                        Order #{{ order.id }}
                    </h1>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold capitalize"
                        :class="statusColor(order.status)"
                    >
                        {{ order.status }}
                    </span>
                </div>
                <p class="text-xs text-brand-500 mt-1">
                    Placed on
                    {{
                        new Date(order.created_at).toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                        })
                    }}
                </p>
            </div>

            <button
                @click="deleteOrder"
                class="flex items-center gap-1.5 bg-red-50 text-red-700 hover:bg-red-100 px-4 py-2 rounded-md text-sm font-medium transition-colors"
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
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                </svg>
                Delete Order
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: Items + Shipping -->
            <div class="md:col-span-2 space-y-6">
                <!-- Items -->
                <div
                    class="bg-white border border-brand-100 rounded-xl overflow-hidden"
                >
                    <div
                        class="px-6 py-4 border-b border-brand-100 bg-brand-50/40"
                    >
                        <h2 class="text-sm font-semibold text-brand-900">
                            Items ({{ order.items.length }})
                        </h2>
                    </div>

                    <div class="divide-y divide-brand-50">
                        <div
                            v-for="item in order.items"
                            :key="item.id"
                            class="flex items-center gap-4 px-6 py-4"
                        >
                            <div
                                class="w-16 h-16 bg-brand-50/60 rounded-lg overflow-hidden shrink-0 border border-brand-100"
                            >
                                <img
                                    v-if="item.product?.images?.length"
                                    :src="`/storage/${item.product.images[0]}`"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center text-brand-200 text-[9px]"
                                >
                                    No Image
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-brand-900">
                                    {{
                                        item.product?.product_name ??
                                        "Product removed"
                                    }}
                                </p>
                                <p class="text-xs text-brand-500 mt-0.5">
                                    Qty: {{ item.quantity }} &times;
                                    {{ item.price }} EGP
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-brand-900">
                                {{
                                    (item.price * item.quantity).toFixed(2)
                                }}
                                EGP
                            </p>
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-brand-50/40 border-t border-brand-100 flex items-center justify-between"
                    >
                        <span class="text-sm font-semibold text-brand-900"
                            >Total</span
                        >
                        <span class="text-lg font-bold text-brand-700"
                            >{{ order.total_price }} EGP</span
                        >
                    </div>
                </div>

                <!-- Shipping -->
                <div class="bg-white border border-brand-100 rounded-xl p-6">
                    <h2
                        class="text-sm font-semibold text-brand-900 mb-3 flex items-center gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-brand-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                        Shipping Address
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

            <!-- Right: Customer + Status -->
            <div class="space-y-6">
                <div class="bg-white border border-brand-100 rounded-xl p-6">
                    <h2
                        class="text-sm font-semibold text-brand-900 mb-4 flex items-center gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-brand-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                        Customer
                    </h2>
                    <div class="space-y-1.5 text-sm">
                        <p class="text-brand-900 font-medium">
                            {{ order.customer_name }}
                        </p>
                        <p class="text-brand-600">{{ order.customer_email }}</p>
                        <p class="text-brand-600">{{ order.customer_phone }}</p>
                    </div>
                    <span
                        class="inline-block mt-3 px-2.5 py-1 rounded-full text-[11px] font-medium"
                        :class="
                            order.user
                                ? 'bg-brand-50 text-brand-700'
                                : 'bg-gray-50 text-gray-600'
                        "
                    >
                        {{
                            order.user
                                ? "Registered Customer"
                                : "Guest Checkout"
                        }}
                    </span>
                </div>

                <div class="bg-white border border-brand-100 rounded-xl p-6">
                    <h2 class="text-sm font-semibold text-brand-900 mb-4">
                        Order Status
                    </h2>
                    <select
                        v-model="form.status"
                        class="w-full border border-brand-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600 mb-3 transition-all"
                    >
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button
                        @click="updateStatus"
                        :disabled="form.processing"
                        class="w-full bg-brand-700 text-white py-2.5 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors disabled:opacity-50"
                    >
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
