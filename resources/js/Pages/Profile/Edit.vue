<script setup>
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Head } from "@inertiajs/vue3";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="الملف الشخصي" />

    <StoreLayout>
        <div class="max-w-3xl mx-auto px-4 md:px-6 py-10">
            <h1 class="text-2xl font-bold text-brand-900 mb-8">ملفي الشخصي</h1>

            <div class="space-y-6">
                <div class="bg-white border border-brand-100 rounded-lg p-6">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white border border-brand-100 rounded-lg p-6">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white border border-brand-100 rounded-lg p-6">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
