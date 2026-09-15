<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    order: Object,
});
</script>

<template>
    <Head title="Bank Transfer | Zalya" />

    <StoreLayout>
        <div class="max-w-3xl mx-auto px-4 md:px-6 py-16">
            <div
                class="bg-white border border-brand-100 rounded-xl p-8 shadow-sm"
            >
                <div
                    class="flex items-center justify-center w-16 h-16 rounded-full bg-brand-50 text-brand-700 mx-auto mb-6"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 10h18M7 15h10M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-brand-900 text-center mb-3">
                    Bank Transfer Payment
                </h1>
                <p class="text-brand-600 text-center mb-8">
                    Your order has been created successfully. Please complete
                    the transfer using the details below.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-brand-50 rounded-lg p-5">
                        <p
                            class="text-xs uppercase tracking-wide text-brand-500 mb-2"
                        >
                            Bank Name
                        </p>
                        <p class="text-lg font-semibold text-brand-900">CIB</p>
                    </div>
                    <div class="bg-brand-50 rounded-lg p-5">
                        <p
                            class="text-xs uppercase tracking-wide text-brand-500 mb-2"
                        >
                            Account Name
                        </p>
                        <p class="text-lg font-semibold text-brand-900">
                            Zalya Store
                        </p>
                    </div>
                    <div class="bg-brand-50 rounded-lg p-5">
                        <p
                            class="text-xs uppercase tracking-wide text-brand-500 mb-2"
                        >
                            IBAN
                        </p>
                        <p class="text-lg font-semibold text-brand-900">
                            EG12345678901234567890123456
                        </p>
                    </div>
                    <div class="bg-brand-50 rounded-lg p-5">
                        <p
                            class="text-xs uppercase tracking-wide text-brand-500 mb-2"
                        >
                            Amount
                        </p>
                        <p class="text-lg font-semibold text-brand-900">
                            {{ order.total_price }} EGP
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-lg border border-amber-200 bg-amber-50 p-4 mb-8"
                >
                    <p class="text-sm text-amber-800">
                        Please send the transfer confirmation or payment proof
                        to our support team before the order is processed.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <Link
                        :href="route('checkout.success', order.id)"
                        class="inline-flex items-center justify-center bg-brand-700 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors"
                    >
                        I’ve Sent the Payment
                    </Link>

                    <Link
                        :href="route('home')"
                        class="inline-flex items-center justify-center border border-brand-200 text-brand-700 px-6 py-3 rounded-md text-sm font-medium hover:bg-brand-50 transition-colors"
                    >
                        Continue Shopping
                    </Link>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
