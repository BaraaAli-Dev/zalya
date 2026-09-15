<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { useCartDrawer } from "@/composables/useCartDrawer";

const page = usePage();
const { isOpen, close } = useCartDrawer();

const updateQuantity = (productId, newQuantity) => {
    if (newQuantity < 1) return;

    router.patch(
        route("cart.update", productId),
        {
            quantity: newQuantity,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};

const removeItem = (productId) => {
    router.delete(route("cart.remove", productId), {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <Teleport to="body">
        <!-- Overlay -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                @click="close"
                class="fixed inset-0 bg-black/40 z-[60]"
            ></div>
        </Transition>

        <!-- Drawer Panel -->
        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-300 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="isOpen"
                class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white z-[70] shadow-xl flex flex-col"
            >
                <!-- Header -->
                <div
                    class="flex items-center justify-between px-5 py-4 border-b border-brand-100"
                >
                    <h2 class="text-base font-semibold text-brand-900">
                        سلة التسوق
                    </h2>
                    <button
                        @click="close"
                        class="text-brand-600 hover:text-brand-900 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Empty state -->
                <div
                    v-if="Object.keys(page.props.cart).length === 0"
                    class="flex-1 flex flex-col items-center justify-center px-6 text-center"
                >
                    <p class="text-brand-600 text-sm mb-4">السلة فارغة.</p>
                    <button
                        @click="close"
                        class="text-sm font-medium text-brand-700 hover:text-brand-900"
                    >
                        متابعة التسوق
                    </button>
                </div>
                <!-- Error Message -->
                <div
                    v-if="$page.props.errors?.stock"
                    class="px-5 py-2 bg-red-50 text-red-700 text-xs text-center"
                >
                    {{ $page.props.errors.stock }}
                </div>

                <!-- Items -->
                <div v-else class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                    <div
                        v-for="(item, productId) in page.props.cart"
                        :key="productId"
                        class="flex gap-3"
                    >
                        <div
                            class="w-16 h-16 bg-brand-50/60 rounded-md overflow-hidden shrink-0"
                        >
                            <img
                                v-if="item.image"
                                :src="`/storage/${item.image}`"
                                :alt="item.product_name"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-brand-100 text-[9px]"
                            >
                                لا توجد صورة
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3
                                        class="text-sm font-medium text-brand-900 truncate"
                                    >
                                        {{ item.product_name }}
                                    </h3>
                                    <p class="text-xs text-brand-600">
                                        {{ item.size }}
                                    </p>
                                </div>
                                <button
                                    @click="removeItem(productId)"
                                    class="text-brand-400 hover:text-red-600 transition-colors shrink-0"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-2">
                                <div
                                    class="flex items-center border border-brand-100 rounded-md"
                                >
                                    <button
                                        @click="
                                            updateQuantity(
                                                productId,
                                                item.quantity - 1,
                                            )
                                        "
                                        class="w-6 h-6 flex items-center justify-center text-brand-900 hover:bg-brand-50 transition-colors text-xs"
                                    >
                                        −
                                    </button>
                                    <span
                                        class="w-6 text-center text-xs font-medium"
                                        >{{ item.quantity }}</span
                                    >
                                    <button
                                        @click="
                                            updateQuantity(
                                                productId,
                                                item.quantity + 1,
                                            )
                                        "
                                        class="w-6 h-6 flex items-center justify-center text-brand-900 hover:bg-brand-50 transition-colors text-xs"
                                    >
                                        +
                                    </button>
                                </div>

                                <p class="text-xs font-semibold text-brand-700">
                                    {{
                                        (item.price * item.quantity).toFixed(2)
                                    }}
                                    جنيه
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    v-if="Object.keys(page.props.cart).length > 0"
                    class="border-t border-brand-100 px-5 py-4"
                >
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-brand-900"
                            >الإجمالي الفرعي</span
                        >
                        <span class="text-sm font-semibold text-brand-900"
                            >{{ page.props.cartTotal.toFixed(2) }} جنيه</span
                        >
                    </div>
                    <Link
                        :href="route('checkout.show')"
                        class="block text-center bg-brand-700 text-white py-3 rounded-md text-sm font-medium hover:bg-brand-800 transition-colors"
                    >
                        إتمام الطلب
                    </Link>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
