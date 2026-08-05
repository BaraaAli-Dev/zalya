<script setup>
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: StoreLayout });

defineProps({
    featuredProducts: Array,
    bestSellers: Array,
    newArrivals: Array,
    categories: Array,
});
</script>

<template>
    <Head title="Zalya | Unforgettable Fragrance" />

    <!-- Hero Section -->
    <section class="bg-brand-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-24 text-center">
            <p class="text-brand-100 tracking-[0.3em] text-sm mb-4">ZALYA PERFUME</p>
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Unforgettable Fragrance</h1>
            <p class="text-brand-100 max-w-xl mx-auto mb-8">
                Discover scents crafted to leave a lasting impression, made with love, just for you.
            </p>
            <Link
                href="#featured"
                class="inline-block bg-brand-50 text-brand-900 px-8 py-3 rounded-md font-medium hover:bg-white transition-colors"
            >
                Shop Now
            </Link>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="max-w-7xl mx-auto px-6 py-16" v-if="featuredProducts.length">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-brand-900">Featured</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
        </div>
    </section>

    <!-- Best Sellers -->
    <section class="bg-brand-50/30 py-16" v-if="bestSellers.length">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-brand-900 mb-8">Best Sellers</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <ProductCard v-for="product in bestSellers" :key="product.id" :product="product" />
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="max-w-7xl mx-auto px-6 py-16" v-if="categories.length">
        <h2 class="text-2xl font-bold text-brand-900 mb-8">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Link
                v-for="category in categories"
                :key="category.id"
                href="#"
                class="bg-brand-900 text-white rounded-lg p-6 text-center hover:bg-brand-800 transition-colors"
            >
                <p class="font-medium">{{ category.name }}</p>
                <p class="text-brand-100 text-xs mt-1">{{ category.products_count }} Products</p>
            </Link>
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="max-w-7xl mx-auto px-6 py-16" v-if="newArrivals.length">
        <h2 class="text-2xl font-bold text-brand-900 mb-8">New Arrivals</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <ProductCard v-for="product in newArrivals" :key="product.id" :product="product" />
        </div>
    </section>
</template>
