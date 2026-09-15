<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";

defineOptions({ layout: AdminLayout });

const props = defineProps({
    products: Object,
});

const deleteProduct = (product) => {
    if (
        confirm(
            `هل أنت متأكد من حذف "${product.product_name}"؟ لا يمكن التراجع عن هذا الإجراء.`,
        )
    ) {
        router.delete(route("admin.products.destroy", product.id));
    }
};

const genderLabel = (gender) => {
    return (
        { men: "رجالي", women: "نسائي", unisex: "للجنسين" }[gender] ?? gender
    );
};

const priceRange = (variants) => {
    if (!variants || variants.length === 0) return "—";
    const prices = variants.map((v) => Number(v.price));
    const min = Math.min(...prices);
    const max = Math.max(...prices);
    return min === max ? `${min} جنيه` : `${min} - ${max} جنيه`;
};

const totalStock = (variants) => {
    if (!variants) return 0;
    return variants.reduce((sum, v) => sum + Number(v.stock), 0);
};
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-brand-900">المنتجات</h1>
            <Link
                :href="route('admin.products.create')"
                class="bg-brand-700 hover:bg-brand-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
            >
                + إضافة منتج جديد
            </Link>
        </div>

        <div
            class="overflow-x-auto rounded-lg border border-brand-100 bg-white"
        >
            <table class="w-full min-w-[900px] text-sm">
                <thead class="bg-brand-50 text-brand-900">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">#</th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الصورة
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">الاسم</th>
                        <th class="text-left px-4 py-3 font-semibold">
                            التصنيف
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">النوع</th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الأحجام
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">السعر</th>
                        <th class="text-left px-4 py-3 font-semibold">
                            المخزون
                        </th>
                        <th class="text-left px-4 py-3 font-semibold">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products.data"
                        :key="product.id"
                        class="border-t border-brand-100"
                    >
                        <td class="px-4 py-3 text-brand-600">
                            {{ product.id }}
                        </td>
                        <td class="px-4 py-3">
                            <img
                                v-if="product.images?.length"
                                :src="`/storage/${product.images[0]}`"
                                class="w-12 h-12 object-cover rounded-md"
                            />
                            <div
                                v-else
                                class="w-12 h-12 bg-brand-50 rounded-md flex items-center justify-center text-brand-100 text-xs"
                            >
                                لا توجد صورة
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium text-brand-900">
                            {{ product.product_name }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ product.category?.name }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ genderLabel(product.gender) }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{
                                product.variants?.map((v) => v.size).join(", ")
                            }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ priceRange(product.variants) }}
                        </td>
                        <td class="px-4 py-3 text-brand-600">
                            {{ totalStock(product.variants) }}
                        </td>
                        <td class="px-4 py-3 space-x-3">
                            <Link
                                :href="route('admin.products.edit', product.id)"
                                class="text-brand-700 hover:underline font-medium"
                            >
                                تعديل
                            </Link>
                            <button
                                @click="deleteProduct(product)"
                                class="text-red-600 hover:underline font-medium"
                            >
                                حذف
                            </button>
                        </td>
                    </tr>

                    <tr v-if="products.data.length === 0">
                        <td
                            colspan="9"
                            class="px-4 py-10 text-center text-brand-400"
                        >
                            لا توجد منتجات.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="products.data.length > 0 && products.links.length > 3"
            class="flex justify-center mt-6 space-x-1"
        >
            <Link
                v-for="(link, i) in products.links"
                :key="i"
                :href="link.url || '#'"
                v-html="link.label"
                class="px-3 py-1.5 rounded-md text-sm"
                :class="[
                    link.active
                        ? 'bg-brand-700 text-white'
                        : 'text-brand-600 hover:bg-brand-50',
                    !link.url && 'opacity-40 cursor-not-allowed',
                ]"
            />
        </div>
    </div>
</template>
