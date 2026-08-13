<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const applyFilter = (key, value) => {
    router.get(
        route("home"),
        {
            category: key === "category" ? value : props.filters.category,
            gender: key === "gender" ? value : props.filters.gender,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const clearFilters = () => {
    router.get(route("home"), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Zalya | Unforgettable Fragrance" />

    <StoreLayout>
        <!-- Hero Section -->
        <section class="bg-brand-900 text-white">
            <div class="max-w-7xl mx-auto px-6 py-20 text-center">
                <p class="text-brand-100 tracking-[0.3em] text-xs mb-4">
                    ZALYA PERFUME
                </p>
                <h1 class="text-3xl md:text-5xl font-bold mb-5">
                    Unforgettable Fragrance
                </h1>
                <p class="text-brand-100 max-w-xl mx-auto">
                    Discover scents crafted to leave a lasting impression, made
                    with love, just for you.
                </p>
            </div>
        </section>

        <!-- Filter Bar -->
        <!-- Filter Bar -->
        <section
            class="border-b border-brand-100 bg-white sticky top-[73px] z-40"
        >
            <div
                class="max-w-7xl mx-auto px-4 md:px-6 py-3 flex flex-wrap items-center gap-3"
            >
                <!-- Category Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-brand-900 shrink-0"
                        >Category:</label
                    >
                    <select
                        :value="filters.category || ''"
                        @change="applyFilter('category', $event.target.value)"
                        class="border border-brand-100 rounded-md pl-3 pr-8 py-1.5 text-sm text-brand-900 focus:outline-none focus:ring-2 focus:ring-brand-600 min-w-[180px]"
                    >
                        <option value="">All Categories</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <span class="w-px h-5 bg-brand-100 hidden sm:block"></span>

                <!-- Gender Pills (fixed, always visible) -->
                <div class="flex items-center gap-2">
                    <button
                        @click="applyFilter('gender', '')"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors"
                        :class="
                            !filters.gender
                                ? 'bg-brand-700 text-white'
                                : 'bg-brand-50 text-brand-900 hover:bg-brand-100'
                        "
                    >
                        All
                    </button>
                    <button
                        v-for="g in ['men', 'women', 'unisex']"
                        :key="g"
                        @click="applyFilter('gender', g)"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors capitalize"
                        :class="
                            filters.gender === g
                                ? 'bg-brand-700 text-white'
                                : 'bg-brand-50 text-brand-900 hover:bg-brand-100'
                        "
                    >
                        {{ g }}
                    </button>
                </div>

                <button
                    v-if="filters.category || filters.gender"
                    @click="clearFilters"
                    class="ml-auto text-xs text-red-600 hover:underline shrink-0"
                >
                    Clear filters
                </button>
            </div>
        </section>

        <!-- Products Grid -->
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div
                v-if="products.data.length"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4"
            >
                <ProductCard
                    v-for="product in products.data"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <div v-else class="text-center py-24 text-brand-600">
                No products match your filters.
            </div>

            <div
                class="mt-10 flex justify-center gap-1"
                v-if="products.links.length > 3"
            >
                <Link
                    v-for="(link, index) in products.links"
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
    </StoreLayout>
</template>
