<script setup>
import { Link, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    product: Object,
});

const genderLabel = (gender) => {
    return (
        { men: "رجالي", women: "نسائي", unisex: "للجنسين" }[gender] ?? gender
    );
};

const totalStock = computed(() => {
    return (
        props.product.variants?.reduce((sum, v) => sum + Number(v.stock), 0) ??
        0
    );
});

const minPrice = computed(() => {
    if (!props.product.variants?.length) return null;
    return Math.min(...props.product.variants.map((v) => Number(v.price)));
});

const hasMultipleSizes = computed(() => props.product.variants?.length > 1);

const singleVariant = computed(() => {
    return props.product.variants?.length === 1
        ? props.product.variants[0]
        : null;
});

const addToCart = () => {
    if (!singleVariant.value || singleVariant.value.stock <= 0) return;

    router.post(
        route("cart.add"),
        {
            product_variant_id: singleVariant.value.id,
            quantity: 1,
        },
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div
        class="bg-white border border-brand-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200"
    >
        <Link
            :href="route('products.show', product.slug)"
            class="block relative"
        >
            <div class="aspect-square bg-brand-50/60">
                <img
                    v-if="product.images?.length"
                    :src="`/storage/${product.images[0]}`"
                    :alt="product.product_name"
                    class="w-full h-full object-cover"
                    :class="{ 'opacity-50': totalStock <= 0 }"
                />
                <div
                    v-else
                    class="w-full h-full flex items-center justify-center text-brand-100 text-xs"
                >
                    لا توجد صورة
                </div>
            </div>

            <span
                v-if="totalStock <= 0"
                class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-medium px-2 py-1 rounded"
            >
                غير متوفر
            </span>
        </Link>

        <div class="p-3">
            <p
                class="text-[11px] text-brand-600 uppercase tracking-wide truncate"
            >
                {{ product.category?.name }} · {{ genderLabel(product.gender) }}
            </p>

            <Link :href="route('products.show', product.slug)">
                <h3
                    class="text-sm font-medium text-brand-900 mt-1 truncate hover:text-brand-700 transition-colors"
                >
                    {{ product.product_name }}
                </h3>
            </Link>

            <div class="flex items-center justify-between mt-1">
                <p class="text-xs text-brand-600">
                    {{
                        singleVariant
                            ? singleVariant.size
                            : `${product.variants?.length} sizes`
                    }}
                </p>
                <p class="text-sm font-semibold text-brand-700">
                    <span v-if="hasMultipleSizes">ابتداءً من </span
                    >{{ minPrice ?? "—" }} جنيه
                </p>
            </div>

            <button
                v-if="singleVariant"
                @click="addToCart"
                :disabled="totalStock <= 0"
                class="mt-3 w-full text-xs font-medium py-2 rounded-md transition-colors"
                :class="
                    totalStock > 0
                        ? 'bg-brand-700 text-white hover:bg-brand-800'
                        : 'bg-brand-50 text-brand-600 cursor-not-allowed'
                "
            >
                {{ totalStock > 0 ? "أضف إلى السلة" : "غير متوفر" }}
            </button>

            <Link
                v-else
                :href="route('products.show', product.slug)"
                class="mt-3 block w-full text-center text-xs font-medium py-2 rounded-md transition-colors"
                :class="
                    totalStock > 0
                        ? 'bg-brand-700 text-white hover:bg-brand-800'
                        : 'bg-brand-50 text-brand-600 cursor-not-allowed pointer-events-none'
                "
            >
                {{ totalStock > 0 ? "اختر الحجم" : "غير متوفر" }}
            </Link>
        </div>
    </div>
</template>
