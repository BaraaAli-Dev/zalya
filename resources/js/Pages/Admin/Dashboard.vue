<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: AdminLayout });

defineProps({
    stats: Object,
    recentOrders: Array,
    lowStockVariants: Array,
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
    <div>
        <h1 class="text-2xl font-bold text-brand-900 mb-8">لوحة التحكم</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">إجمالي المنتجات</p>
                <p class="text-2xl font-bold text-brand-900">
                    {{ stats.total_products }}
                </p>
            </div>
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">التصنيفات</p>
                <p class="text-2xl font-bold text-brand-900">
                    {{ stats.total_categories }}
                </p>
            </div>
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">إجمالي الطلبات</p>
                <p class="text-2xl font-bold text-brand-900">
                    {{ stats.total_orders }}
                </p>
            </div>
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">الطلبات المعلقة</p>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ stats.pending_orders }}
                </p>
            </div>
        </div>

        <!-- Revenue + Stock Alerts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-brand-900 text-white rounded-lg p-5 md:col-span-1">
                <p class="text-xs text-brand-100 mb-1">
                    إجمالي الإيرادات (المدفوعة)
                </p>
                <p class="text-3xl font-bold">
                    {{ Number(stats.total_revenue).toFixed(2) }} جنيه
                </p>
            </div>
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">مخزون منخفض (≤ 5)</p>
                <p class="text-2xl font-bold text-orange-500">
                    {{ stats.low_stock_count }}
                </p>
            </div>
            <div class="bg-white border border-brand-100 rounded-lg p-5">
                <p class="text-xs text-brand-500 mb-1">نفد المخزون</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ stats.out_of_stock_count }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div
                class="bg-white border border-brand-100 rounded-lg overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-brand-100 bg-brand-50/40 flex items-center justify-between"
                >
                    <h2 class="text-sm font-semibold text-brand-900">
                        أحدث الطلبات
                    </h2>
                    <Link
                        :href="route('admin.orders.index')"
                        class="text-xs text-brand-700 hover:underline font-medium"
                    >
                        عرض الكل
                    </Link>
                </div>

                <div
                    v-if="recentOrders.length === 0"
                    class="px-5 py-8 text-center text-sm text-brand-400"
                >
                    لا توجد طلبات بعد.
                </div>

                <div v-else class="divide-y divide-brand-50">
                    <Link
                        v-for="order in recentOrders"
                        :key="order.id"
                        :href="route('admin.orders.show', order.id)"
                        class="flex items-center justify-between px-5 py-3 hover:bg-brand-50/30 transition-colors"
                    >
                        <div>
                            <p class="text-sm font-medium text-brand-900">
                                #{{ order.id }} - {{ order.customer_name }}
                            </p>
                            <p class="text-xs text-brand-500">
                                {{
                                    new Date(
                                        order.created_at,
                                    ).toLocaleDateString("ar-EG")
                                }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="px-2 py-1 rounded-full text-[11px] font-medium capitalize"
                                :class="statusColor(order.status)"
                            >
                                {{ statusLabel(order.status) }}
                            </span>
                            <span class="text-sm font-semibold text-brand-900"
                                >{{ order.total_price }} جنيه</span
                            >
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Low Stock Variants -->
            <div
                class="bg-white border border-brand-100 rounded-lg overflow-hidden"
            >
                <div
                    class="px-5 py-4 border-b border-brand-100 bg-brand-50/40 flex items-center justify-between"
                >
                    <h2 class="text-sm font-semibold text-brand-900">
                        تنبيهات المخزون المنخفض
                    </h2>
                    <Link
                        :href="route('admin.products.index')"
                        class="text-xs text-brand-700 hover:underline font-medium"
                    >
                        View All
                    </Link>
                </div>

                <div
                    v-if="lowStockVariants.length === 0"
                    class="px-5 py-8 text-center text-sm text-brand-400"
                >
                    جميع المنتجات متوفرة بمخزون جيد. 🎉
                </div>

                <div v-else class="divide-y divide-brand-50">
                    <Link
                        v-for="variant in lowStockVariants"
                        :key="variant.id"
                        :href="route('admin.products.edit', variant.product_id)"
                        class="flex items-center justify-between px-5 py-3 hover:bg-brand-50/30 transition-colors"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-brand-50/60 rounded-md overflow-hidden shrink-0"
                            >
                                <img
                                    v-if="variant.product?.images?.length"
                                    :src="`/storage/${variant.product.images[0]}`"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-brand-900">
                                    {{ variant.product?.product_name }}
                                </p>
                                <p class="text-xs text-brand-500">
                                    {{ variant.size }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full"
                            :class="
                                variant.stock === 0
                                    ? 'bg-red-50 text-red-700'
                                    : 'bg-orange-50 text-orange-700'
                            "
                        >
                            {{
                                variant.stock === 0
                                    ? "Out of stock"
                                    : `${variant.stock} left`
                            }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
