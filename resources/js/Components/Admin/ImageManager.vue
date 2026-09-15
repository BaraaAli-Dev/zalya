<script setup>
import { ref, markRaw } from "vue";

const props = defineProps({
    existingImages: { type: Array, default: () => [] },
});

const emit = defineEmits(["change"]);

const items = ref(
    props.existingImages.map((path, index) => ({
        id: `existing-${index}`,
        type: "existing",
        path,
        previewUrl: `/storage/${path}`,
    })),
);

const fileInput = ref(null);

const handleFiles = (e) => {
    const files = Array.from(e.target.files);
    files.forEach((file) => {
        items.value.push({
            id: `new-${Date.now()}-${Math.random()}`,
            type: "new",
            file: markRaw(file),
            previewUrl: URL.createObjectURL(file),
        });
    });
    e.target.value = "";
    emitChange();
};

const removeItem = (id) => {
    items.value = items.value.filter((item) => item.id !== id);
    emitChange();
};

const makePrimary = (id) => {
    const index = items.value.findIndex((item) => item.id === id);
    if (index > 0) {
        const [item] = items.value.splice(index, 1);
        items.value.unshift(item);
        emitChange();
    }
};

const emitChange = () => {
    const order = items.value.map((item) => item.type);
    const existing_images = items.value
        .filter((i) => i.type === "existing")
        .map((i) => i.path);
    const new_images = items.value
        .filter((i) => i.type === "new")
        .map((i) => i.file);

    emit("change", { order, existing_images, new_images });
};

emitChange();
</script>

<template>
    <div>
        <div class="flex flex-wrap gap-3 mb-3">
            <div
                v-for="(item, index) in items"
                :key="item.id"
                class="relative w-24 h-24 rounded-md overflow-hidden border-2"
                :class="index === 0 ? 'border-brand-700' : 'border-brand-100'"
            >
                <img
                    :src="item.previewUrl"
                    class="w-full h-full object-cover"
                />

                <!-- Main Image Indicator -->
                <span
                    v-if="index === 0"
                    class="absolute bottom-0 inset-x-0 bg-brand-700 text-white text-[10px] text-center py-0.5"
                >
                    رئيسية
                </span>

                <!-- Remove Image -->
                <button
                    type="button"
                    @click="removeItem(item.id)"
                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs leading-none hover:bg-red-700 transition-colors"
                >
                    ✕
                </button>

                <!-- Make Primary -->
                <button
                    v-if="index !== 0"
                    type="button"
                    @click="makePrimary(item.id)"
                    class="absolute top-1 left-1 bg-white/90 text-brand-700 rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-white transition-colors"
                    title="تعيين كصورة رئيسية"
                >
                    ★
                </button>
            </div>
        </div>

        <input
            ref="fileInput"
            type="file"
            multiple
            accept="image/*"
            @change="handleFiles"
            class="w-full border border-brand-100 rounded-md px-3 py-2 text-sm"
        />
        <p class="text-xs text-brand-600 mt-1">
            يمكنك تعيين صورة رئيسية بالضغط على أيقونة النجمة.
        </p>
    </div>
</template>
