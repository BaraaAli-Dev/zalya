<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    cart: Object,
    total: Number,
});

const page = usePage();
const user = page.props.auth?.user;

const form = useForm({
    customer_name: user?.name ?? "",
    customer_email: user?.email ?? "",
    customer_phone: "",
    shipping_street: "",
    shipping_city: "",
    shipping_state: "",
    shipping_country: "Egypt",
});

const submit = () => {
    form.post(route("checkout.store"));
};
</script>

<template>
    <Head title="Checkout | Zalya" />

    <StoreLayout>
        <div class="max-w-5xl mx-auto px-4 md:px-6 py-10">
            <h1 class="text-2xl font-bold text-brand-900 mb-8">Checkout</h1>
            <div
                v-if="$page.props.errors?.cart"
                class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm"
            >
                {{ $page.props.errors.cart }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Form -->
                <form @submit.prevent="submit" class="md:col-span-2 space-y-6">
                    <div
                        class="bg-white border border-brand-100 rounded-lg p-6"
                    >
                        <h2 class="text-sm font-semibold text-brand-900 mb-4">
                            Contact Information
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-brand-700 mb-1"
                                    >Full Name</label
                                >
                                <input
                                    v-model="form.customer_name"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.customer_name"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.customer_name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs text-brand-700 mb-1"
                                    >Email</label
                                >
                                <input
                                    v-model="form.customer_email"
                                    type="email"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.customer_email"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.customer_email }}
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs text-brand-700 mb-1"
                                    >Phone</label
                                >
                                <input
                                    v-model="form.customer_phone"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.customer_phone"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.customer_phone }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white border border-brand-100 rounded-lg p-6"
                    >
                        <h2 class="text-sm font-semibold text-brand-900 mb-4">
                            Shipping Address
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs text-brand-700 mb-1"
                                    >Street Address</label
                                >
                                <input
                                    v-model="form.shipping_street"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.shipping_street"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.shipping_street }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs text-brand-700 mb-1"
                                    >City</label
                                >
                                <input
                                    v-model="form.shipping_city"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.shipping_city"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.shipping_city }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs text-brand-700 mb-1"
                                    >State / Governorate</label
                                >
                                <input
                                    v-model="form.shipping_state"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs text-brand-700 mb-1"
                                    >Country</label
                                >
                                <input
                                    v-model="form.shipping_country"
                                    type="text"
                                    class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
                                />
                                <p
                                    v-if="form.errors.shipping_country"
                                    class="text-red-600 text-xs mt-1"
                                >
                                    {{ form.errors.shipping_country }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-brand-700 text-white py-3 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors disabled:opacity-50"
                    >
                        Place Order
                    </button>
                </form>

                <!-- Order Summary -->
                <div class="md:col-span-1">
                    <div
                        class="bg-brand-50/40 border border-brand-100 rounded-lg p-6 md:sticky md:top-24"
                    >
                        <h2 class="text-sm font-semibold text-brand-900 mb-4">
                            Order Summary
                        </h2>

                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            <div
                                v-for="(item, productId) in cart"
                                :key="productId"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="w-12 h-12 bg-brand-50/60 rounded-md overflow-hidden shrink-0"
                                >
                                    <img
                                        v-if="item.image"
                                        :src="`/storage/${item.image}`"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-xs font-medium text-brand-900 truncate"
                                    >
                                        {{ item.product_name }}
                                    </p>
                                    <p class="text-xs text-brand-600">
                                        Qty: {{ item.quantity }}
                                    </p>
                                </div>
                                <p
                                    class="text-xs font-semibold text-brand-700 shrink-0"
                                >
                                    {{
                                        (item.price * item.quantity).toFixed(2)
                                    }}
                                    EGP
                                </p>
                            </div>
                        </div>

                        <div
                            class="border-t border-brand-100 pt-4 flex items-center justify-between font-semibold text-brand-900"
                        >
                            <span>Total</span>
                            <span>{{ total.toFixed(2) }} EGP</span>
                        </div>

                        <p class="text-xs text-brand-500 mt-3">
                            Payment: Cash on Delivery
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
