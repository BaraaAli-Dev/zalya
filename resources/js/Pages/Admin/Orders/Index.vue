<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";

defineOptions({ layout: AdminLayout });

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

const statusLabel = (status) =>
    ({
        pending: "قيد الانتظار",
        processing: "قيد التجهيز",
        delivered: "تم التوصيل",
        cancelled: "ملغي",
    })[status] ?? status;

const deleteOrder = (order) => {
    if (
        confirm(
            `هل أنت متأكد من حذف الطلب #${order.id}؟ لا يمكن التراجع عن هذا الإجراء.`,
        )
    ) {
        router.delete(route("admin.orders.destroy", order.id));
    }
};
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-brand-900 mb-6">الطلبات</h1>

        <div
            class="bg-white rounded-lg border border-brand-100 overflow-hidden"
        >
            <table class="w-full text-sm">
                <thead class="bg-brand-50 text-brand-900">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">#</th>
                        <th class="text-left px-4 py-3 font-semibold">
                            العميل
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            البريد الإلكتروني
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الإجمالي
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الحالة
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            التاريخ
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders.data"
                        :key="order.id"
                        class="border-t border-brand-100"
                    >
                        <td class="px-4 py-3 text-brand-600">
                            #{{ order.id }}
                        </td>
                        <td class="px-4 py-3 font-medium text-brand-900">
                            {{ order.customer_name }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ order.customer_email }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ order.total_price }} جنيه
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium capitalize"
                                :class="statusColor(order.status)"
                            >
                                {{ statusLabel(order.status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-brand-600 text-xs">
                            {{
                                new Date(order.created_at).toLocaleDateString(
                                    "ar-EG",
                                )
                            }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="route('admin.orders.show', order.id)"
                                    class="bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                >
                                    عرض
                                </Link>
                                <button
                                    @click="deleteOrder(order)"
                                    class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-medium transition-colors"
                                >
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="orders.data.length === 0">
                        <td
                            colspan="7"
                            class="px-4 py-10 text-center text-brand-400"
                        >
                            لا توجد طلبات بعد.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="mt-6 flex justify-center gap-1"
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
</template>
