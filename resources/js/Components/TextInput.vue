<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({ type: String, required: true });

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-brand-100 focus:border-brand-700 focus:ring-brand-700 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
