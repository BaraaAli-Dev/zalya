<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from "@/assets/images/logo.png";

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Confirm Password" />

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
                This is a secure area. Please confirm your password before
                continuing.
            </p>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Confirm
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
