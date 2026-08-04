<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
});

const submit = () => {
    form.put(route("admin.categories.update", props.category.id));
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-center min-h-[75vh]">
            <div class="bg-white rounded-lg shadow-sm border border-brand-100 p-8 w-full max-w-lg">
                <h1 class="text-2xl font-bold text-brand-700 mb-6 text-center">
                    Edit Category
                </h1>

                <form @submit.prevent="submit">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Category Name
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="block w-full rounded-md border-gray-300 shadow-sm transition-all duration-200 ease-in-out focus:border-brand-700 focus:ring-2 focus:ring-brand-700/40 focus:outline-none"
                        required
                        autofocus
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="bg-brand-700 text-white px-6 py-2.5 rounded-md hover:bg-brand-800 transition-all duration-200 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Save Changes
                    </button>

                    <Link
                        :href="route('admin.categories.index')"
                        class="text-gray-600 hover:text-gray-800 text-sm"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
            </div>
        </div>
    </AdminLayout>
</template>
