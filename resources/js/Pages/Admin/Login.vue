<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("admin.login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Admin Login" />

    <div class="min-h-screen flex items-center justify-center bg-brand-50/30">
        <div
            class="w-full max-w-md bg-white p-8 rounded-lg shadow-md border border-brand-100"
        >
            <h1 class="text-2xl font-bold mb-6 text-center text-brand-700">
                Admin Login
            </h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="block w-full rounded-md border-gray-300 shadow-sm transition-all duration-200 ease-in-out focus:border-brand-700 focus:ring-2 focus:ring-brand-700/40 focus:outline-none"
                        required
                        autofocus
                    />
                    <div
                        v-if="form.errors.email"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="block w-full rounded-md border-gray-300 shadow-sm transition-all duration-200 ease-in-out focus:border-brand-700 focus:ring-2 focus:ring-brand-700/40 focus:outline-none"
                        required
                    />
                    <div
                        v-if="form.errors.password"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="flex items-center mb-6">
                    <input
                        v-model="form.remember"
                        type="checkbox"
                        id="remember"
                        class="rounded border-gray-300 text-brand-700 focus:ring-brand-700"
                    />
                    <label for="remember" class="mr-2 text-sm text-gray-600">
                        Remember me
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-brand-700 text-white py-2.5 rounded-md transition-all duration-200 ease-in-out hover:bg-brand-800 hover:shadow-lg disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</template>
