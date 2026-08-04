<!-- resources/js/Components/FlashMessage.vue -->
<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success');

let timeoutId = null;

function displayMessage(msg, msgType) {
    message.value = msg;
    type.value = msgType;
    show.value = true;

    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        show.value = false;
    }, 5000);
}

function checkFlash() {
    if (page.props.flash?.success) {
        displayMessage(page.props.flash.success, 'success');
    } else if (page.props.flash?.error) {
        displayMessage(page.props.flash.error, 'error');
    }
}

onMounted(checkFlash);

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    checkFlash
);
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-3"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-500 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-3"
    >
        <div
            v-if="show"
            class="mb-4 rounded-md border px-4 py-3"
            :class="type === 'success'
                ? 'bg-green-50 border-green-200 text-green-700'
                : 'bg-red-50 border-red-200 text-red-700'"
        >
            {{ message }}
        </div>
    </Transition>
</template>
