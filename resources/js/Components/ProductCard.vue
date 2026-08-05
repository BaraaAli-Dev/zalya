<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: Object,
});
</script>

<template>
    <Link href="#" class="group block">
        <div class="aspect-square bg-brand-50/40 rounded-lg overflow-hidden mb-3">
            <img
                v-if="product.images?.length"
                :src="`/storage/${product.images[0]}`"
                :alt="product.product_name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-brand-100 text-sm">
                No Image
            </div>
        </div>

        <p class="text-xs text-brand-600 uppercase tracking-wide">{{ product.category?.name }}</p>
        <h3 class="text-brand-900 font-medium mt-1 group-hover:text-brand-700 transition-colors">
            {{ product.product_name }}
        </h3>
        <p class="text-brand-700 font-semibold mt-1">{{ product.price }} EGP</p>
    </Link>
</template>
