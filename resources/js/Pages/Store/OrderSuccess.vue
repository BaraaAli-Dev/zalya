<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    order: Object,
});
</script>

<template>
    <Head title="تم تأكيد الطلب | Zalya" />

    <StoreLayout>
        <div class="max-w-2xl mx-auto px-4 md:px-6 py-16 text-center">
            <div
                class="w-16 h-16 bg-brand-700 rounded-full flex items-center justify-center mx-auto mb-6"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-brand-900 mb-2">
                شكرًا لك، {{ order.customer_name }}!
            </h1>
            <p class="text-brand-600 mb-8">تم تسجيل طلبك بنجاح.</p>

            <div
                class="bg-white border border-brand-100 rounded-lg p-6 text-left"
            >
                <h2 class="text-sm font-semibold text-brand-900 mb-4">
                    تفاصيل الطلب
                </h2>

                <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center justify-between py-2 border-b border-brand-50 last:border-0"
                >
                    <div>
                        <p class="text-sm text-brand-900">
                            {{ item.product.product_name }}
                        </p>
                        <p class="text-xs text-brand-600">
                            الكمية: {{ item.quantity }}
                        </p>
                    </div>
                    <p class="text-sm font-medium text-brand-700">
                        {{ (item.price * item.quantity).toFixed(2) }} EGP
                    </p>
                </div>

                <div
                    class="flex items-center justify-between pt-4 font-semibold text-brand-900"
                >
                    <span>الإجمالي</span>
                    <span>{{ order.total_price }} جنيه</span>
                </div>
            </div>

            <Link
                :href="route('home')"
                class="inline-block mt-8 bg-brand-700 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors"
            >
                متابعة التسوق
            </Link>
        </div>
    </StoreLayout>
</template>
