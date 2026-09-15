<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from "@/assets/images/logo.png";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="استعادة كلمة المرور" />

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
                هل نسيت كلمة المرور؟ لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك
                رابطًا لإعادة تعيين كلمة المرور.
            </p>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="البريد الإلكتروني" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        إرسال رابط إعادة تعيين كلمة المرور
                    </PrimaryButton>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-brand-600">
                تذكرت كلمة المرور؟
                <Link
                    :href="route('login')"
                    class="text-brand-700 font-medium hover:underline"
                >
                    تسجيل الدخول
                </Link>
            </p>
        </div>
    </div>
</template>
