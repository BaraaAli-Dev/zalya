<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from "@/assets/images/logo.png";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div
        class="min-h-screen flex flex-col items-center justify-center bg-brand-50/30 px-4"
    >
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <div
            class="w-full max-w-md bg-white border border-brand-100 rounded-lg shadow-sm p-8"
        >
            <div class="flex justify-center mb-6">
                <Link :href="route('home')">
                    <img :src="logo" alt="Zalya" class="h-16 w-auto" />
                </Link>
            </div>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                        />
                        <span class="ms-2 text-sm text-brand-700"
                            >Remember me</span
                        >
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-brand-600 hover:text-brand-800 underline"
                    >
                        Forgot your password?
                    </Link>

                    <PrimaryButton
                        class="ms-auto"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-brand-600">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="text-brand-700 font-medium hover:underline"
                >
                    Register
                </Link>
            </p>
        </div>
    </div>
</template>
