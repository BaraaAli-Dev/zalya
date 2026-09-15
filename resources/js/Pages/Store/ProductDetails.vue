<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    product: Object,
});

const activeImage = ref(props.product.images?.[0] ?? null);
const quantity = ref(1);
const selectedVariantId = ref(props.product.variants?.[0]?.id ?? null);

const genderLabel = (gender) => {
    return (
        { men: "رجالي", women: "نسائي", unisex: "للجنسين" }[gender] ?? gender
    );
};

const selectedVariant = computed(() => {
    return props.product.variants?.find(
        (v) => v.id === selectedVariantId.value,
    );
});

const addToCart = () => {
    if (!selectedVariant.value || selectedVariant.value.stock <= 0) return;

    router.post(
        route("cart.add"),
        {
            product_variant_id: selectedVariant.value.id,
            quantity: quantity.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                quantity.value = 1;
            },
        },
    );
};
</script>

<template>
    <Head :title="`${product.product_name} | Zalya`" />

    <StoreLayout>
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-10">
            <div class="text-xs text-brand-600 mb-8">
                <Link :href="route('home')" class="hover:text-brand-700"
                    >الرئيسية</Link
                >
                <span class="mx-1">/</span>
                <span class="text-brand-900">{{ product.product_name }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16">
                <!-- Images -->
                <div class="md:sticky md:top-24 self-start">
                    <div class="max-w-xs mx-auto md:mx-0">
                        <div
                            class="aspect-square bg-brand-50/60 rounded-lg overflow-hidden mb-3 border border-brand-100"
                        >
                            <img
                                v-if="activeImage"
                                :src="`/storage/${activeImage}`"
                                :alt="product.product_name"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-brand-100 text-sm"
                            >
                                لا توجد صورة
                            </div>
                        </div>

                        <div
                            v-if="product.images?.length > 1"
                            class="flex gap-2"
                        >
                            <button
                                v-for="(img, index) in product.images"
                                :key="index"
                                @click="activeImage = img"
                                class="w-14 h-14 rounded-md overflow-hidden border-2 shrink-0 transition-colors"
                                :class="
                                    activeImage === img
                                        ? 'border-brand-700'
                                        : 'border-brand-100'
                                "
                            >
                                <img
                                    :src="`/storage/${img}`"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div
                    class="border-t md:border-t-0 md:border-l border-brand-100 md:pl-16 pt-8 md:pt-0"
                >
                    <p class="text-xs text-brand-600 uppercase tracking-wide">
                        {{ product.category?.name }} ·
                        {{ genderLabel(product.gender) }}
                    </p>

                    <h1 class="text-3xl font-bold text-brand-900 mt-2">
                        {{ product.product_name }}
                    </h1>

                    <p class="text-2xl font-semibold text-brand-700 mt-4">
                        {{ selectedVariant?.price ?? "—" }} EGP
                    </p>

                    <!-- Size Selector -->
                    <div class="mt-6">
                        <p class="text-sm text-brand-700 mb-2">الحجم</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="variant in product.variants"
                                :key="variant.id"
                                @click="selectedVariantId = variant.id"
                                :disabled="variant.stock <= 0"
                                class="px-4 py-2 rounded-md text-sm font-medium border transition-colors"
                                :class="[
                                    selectedVariantId === variant.id
                                        ? 'bg-brand-700 text-white border-brand-700'
                                        : 'bg-white text-brand-900 border-brand-100 hover:border-brand-700',
                                    variant.stock <= 0 &&
                                        'opacity-40 cursor-not-allowed line-through',
                                ]"
                            >
                                {{ variant.size }}
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 text-sm">
                        <span class="text-brand-600">التوفر:</span>
                        <span
                            :class="
                                selectedVariant?.stock > 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                            class="font-medium"
                        >
                            {{
                                selectedVariant?.stock > 0
                                    ? `متوفر (${selectedVariant.stock} متبقي)`
                                    : "غير متوفر"
                            }}
                        </span>
                    </div>

                    <p
                        class="text-sm text-brand-700 leading-relaxed mt-6 pb-6 border-b border-brand-100"
                    >
                        {{ product.description }}
                    </p>

                    <div
                        class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-4"
                    >
                        <div
                            class="flex items-center border border-brand-100 rounded-md"
                        >
                            <button
                                @click="quantity > 1 && quantity--"
                                class="w-10 h-11 flex items-center justify-center text-brand-900 hover:bg-brand-50 transition-colors"
                            >
                                −
                            </button>
                            <span
                                class="w-10 text-center text-sm font-medium"
                                >{{ quantity }}</span
                            >
                            <button
                                @click="
                                    selectedVariant &&
                                    quantity < selectedVariant.stock &&
                                    quantity++
                                "
                                class="w-10 h-11 flex items-center justify-center text-brand-900 hover:bg-brand-50 transition-colors"
                            >
                                +
                            </button>
                        </div>

                        <button
                            @click="addToCart"
                            :disabled="
                                !selectedVariant || selectedVariant.stock <= 0
                            "
                            class="flex-1 bg-brand-700 text-white text-sm font-medium py-3 rounded-md hover:bg-brand-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{
                                selectedVariant?.stock > 0
                                    ? "أضف إلى السلة"
                                    : "غير متوفر"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
