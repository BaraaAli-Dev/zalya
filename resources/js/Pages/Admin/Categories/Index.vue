<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    categories: Object,
});

const deleteCategory = (category) => {
    if (confirm(`هل أنت متأكد من حذف "${category.name}"؟`)) {
        router.delete(route("admin.categories.destroy", category.id));
    }
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-brand-700">التصنيفات</h1>

            <Link
                :href="route('admin.categories.create')"
                class="bg-brand-700 text-white px-4 py-2 rounded-md hover:bg-brand-800 transition-all duration-200"
            >
                + إضافة تصنيف جديد
            </Link>
        </div>

        <!-- Success message -->

        <!-- Table -->
        <div
            class="bg-white rounded-lg shadow-sm border border-brand-100 overflow-hidden"
        >
            <table class="w-full text-left">
                <thead class="bg-brand-50/50 text-brand-900 text-sm">
                    <tr>
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">الاسم</th>
                        <th class="px-6 py-3 font-semibold">المعرّف</th>
                        <th class="px-6 py-3 font-semibold">تاريخ الإنشاء</th>
                        <th class="px-6 py-3 font-semibold text-center">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-50">
                    <tr
                        v-for="category in categories.data"
                        :key="category.id"
                        class="hover:bg-brand-50/30 transition-colors duration-150"
                    >
                        <td class="px-6 py-4 text-gray-500">
                            {{ category.id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ category.name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ category.slug }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{
                                new Date(
                                    category.created_at,
                                ).toLocaleDateString("en-US")
                            }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <Link
                                    :href="
                                        route(
                                            'admin.categories.edit',
                                            category.id,
                                        )
                                    "
                                    class="text-brand-700 hover:text-brand-900 text-sm font-medium"
                                >
                                    تعديل
                                </Link>
                                <button
                                    @click="deleteCategory(category)"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                                >
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="categories.data.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-gray-400"
                        >
                            لا توجد تصنيفات
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="mt-6 flex justify-center gap-1"
            v-if="categories.links.length > 3"
        >
            <Link
                v-for="(link, index) in categories.links"
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
    </AdminLayout>
</template>
