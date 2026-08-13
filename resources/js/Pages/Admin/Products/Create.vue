<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import ImageManager from "@/Components/Admin/ImageManager.vue";
import VariantsManager from "@/Components/Admin/VariantsManager.vue";

defineOptions({ layout: AdminLayout });

const props = defineProps({
    categories: Array,
});

const form = useForm({
    product_name: "",
    description: "",
    category_id: "",
    gender: "men",
    is_featured: false,
    is_best_seller: false,
    variants: [{ id: null, size: "", price: "", stock: "" }],
    order: [],
    existing_images: [],
    new_images: [],
});

const onImagesChange = ({ order, existing_images, new_images }) => {
    form.order = order;
    form.existing_images = existing_images;
    form.new_images = new_images;
};

const submit = () => {
    form.post(route("admin.products.store"));
};
</script>

<template>
    <div class="max-w-2xl mx-auto">
        <form
            @submit.prevent="submit"
            class="bg-white rounded-lg border border-brand-100 p-6 space-y-5"
        >
            <h1 class="text-2xl font-bold text-brand-900 mb-6">
                Add New Product
            </h1>

            <div>
                <label class="block text-sm font-medium text-brand-900 mb-1"
                    >Product Name</label
                >
                <input
                    v-model="form.product_name"
                    type="text"
                    class="w-full border border-brand-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600"
                />
                <p
                    v-if="form.errors.product_name"
                    class="text-red-600 text-xs mt-1"
                >
                    {{ form.errors.product_name }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-900 mb-1"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    rows="4"
                    class="w-full border border-brand-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600"
                ></textarea>
                <p
                    v-if="form.errors.description"
                    class="text-red-600 text-xs mt-1"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-brand-900 mb-1"
                        >Category</label
                    >
                    <select
                        v-model="form.category_id"
                        class="w-full border border-brand-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600"
                    >
                        <option value="">Select category</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.category_id"
                        class="text-red-600 text-xs mt-1"
                    >
                        {{ form.errors.category_id }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brand-900 mb-1"
                        >Gender</label
                    >
                    <select
                        v-model="form.gender"
                        class="w-full border border-brand-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-600"
                    >
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="unisex">Unisex</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-900 mb-2"
                    >Sizes & Pricing</label
                >
                <VariantsManager v-model="form.variants" />
                <p
                    v-if="form.errors.variants"
                    class="text-red-600 text-xs mt-1"
                >
                    {{ form.errors.variants }}
                </p>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm text-brand-900">
                    <input
                        v-model="form.is_featured"
                        type="checkbox"
                        class="rounded border-brand-100 text-brand-700 focus:ring-brand-600"
                    />
                    Featured Product
                </label>
                <label class="flex items-center gap-2 text-sm text-brand-900">
                    <input
                        v-model="form.is_best_seller"
                        type="checkbox"
                        class="rounded border-brand-100 text-brand-700 focus:ring-brand-600"
                    />
                    Best Seller
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-900 mb-1"
                    >Images</label
                >
                <ImageManager @change="onImagesChange" />
                <p
                    v-if="form.errors.new_images"
                    class="text-red-600 text-xs mt-1"
                >
                    {{ form.errors.new_images }}
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-brand-700 hover:bg-brand-800 text-white px-5 py-2.5 rounded-md text-sm font-medium transition-colors disabled:opacity-50"
                >
                    Save Product
                </button>
                <Link
                    :href="route('admin.products.index')"
                    class="text-brand-600 text-sm hover:underline"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
