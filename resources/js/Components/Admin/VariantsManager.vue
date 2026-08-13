<script setup>
const props = defineProps({
    modelValue: { type: Array, required: true },
});

const emit = defineEmits(["update:modelValue"]);

const addVariant = () => {
    emit("update:modelValue", [
        ...props.modelValue,
        { id: null, size: "", price: "", stock: "" },
    ]);
};

const removeVariant = (index) => {
    const updated = [...props.modelValue];
    updated.splice(index, 1);
    emit("update:modelValue", updated);
};

const updateVariant = (index, field, value) => {
    const updated = [...props.modelValue];
    updated[index] = { ...updated[index], [field]: value };
    emit("update:modelValue", updated);
};
</script>

<template>
    <div class="space-y-3">
        <div
            v-for="(variant, index) in modelValue"
            :key="index"
            class="flex items-center gap-2"
        >
            <input
                :value="variant.size"
                @input="updateVariant(index, 'size', $event.target.value)"
                type="text"
                placeholder="Size (e.g. 50ml)"
                class="flex-1 border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
            />
            <input
                :value="variant.price"
                @input="updateVariant(index, 'price', $event.target.value)"
                type="number"
                step="0.01"
                placeholder="Price"
                class="w-28 border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
            />
            <input
                :value="variant.stock"
                @input="updateVariant(index, 'stock', $event.target.value)"
                type="number"
                placeholder="Stock"
                class="w-24 border border-brand-100 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"
            />
            <button
                type="button"
                @click="removeVariant(index)"
                class="text-red-500 hover:text-red-700 transition-colors shrink-0"
                title="Remove size"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <button
            type="button"
            @click="addVariant"
            class="text-sm text-brand-700 hover:text-brand-900 font-medium flex items-center gap-1"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
            </svg>
            Add Size
        </button>
    </div>
</template>
