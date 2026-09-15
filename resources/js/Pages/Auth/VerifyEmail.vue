<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import logo from "@/assets/images/logo.png";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent",
);
</script>

<template>
    <Head title="تأكيد البريد الإلكتروني" />

    <div
        class="min-h-screen flex flex-col items-center justify-center bg-brand-50/30 px-4"
    >
        <div
            class="w-full max-w-md bg-white border border-brand-100 rounded-lg shadow-sm p-8"
        >
            <div class="flex justify-center mb-6">
                <Link :href="route('home')">
                    <img :src="logo" alt="Zalya" class="h-16 w-auto" />
                </Link>
            </div>

            <p class="mb-4 text-sm text-brand-600">
                شكرًا لتسجيلك! قبل البدء، يرجى تأكيد بريدك الإلكتروني بالضغط على
                الرابط الذي أرسلناه إليك. إذا لم يصلك البريد، سنرسل رابطًا
                جديدًا بكل سرور.
            </p>

            <div
                v-if="verificationLinkSent"
                class="mb-4 text-sm font-medium text-green-600"
            >
                تم إرسال رابط تأكيد جديد إلى البريد الإلكتروني الذي قدمته عند
                التسجيل.
            </div>

            <form @submit.prevent="submit">
                <div class="flex items-center justify-between mt-4">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        إعادة إرسال بريد التأكيد
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-brand-600 hover:text-brand-800 underline"
                    >
                        تسجيل الخروج
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
