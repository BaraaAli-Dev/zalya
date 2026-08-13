<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(["close"]);

const query = ref("");
const results = ref([]);
const loading = ref(false);
let debounceTimer = null;

watch(query, (value) => {
    clearTimeout(debounceTimer);

    if (value.length < 2) {
        results.value = [];
        return;
    }

    debounceTimer = setTimeout(async () => {
        loading.value = true;
        try {
            const { data } = await axios.get(route("search"), {
                params: { q: value },
            });
            results.value = data;
        } finally {
            loading.value = false;
        }
    }, 300);
});

const goToProduct = (slug) => {
    close();
    router.visit(route("products.show", slug));
};

const close = () => {
    query.value = "";
    results.value = [];
    emit("close");
};
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="isOpen"
            class="absolute top-full right-0 mt-2 w-72 sm:w-80 bg-white rounded-lg shadow-lg border border-brand-100 z-[80]"
        >
            <!-- Search Input -->
            <div
                class="flex items-center gap-2 px-3 py-2.5 border-b border-brand-100"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-brand-400 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                </svg>
                <input
                    v-model="query"
                    type="text"
                    autofocus
                    placeholder="Search perfumes..."
                    class="flex-1 outline-none focus:outline-none focus:ring-0 border-none text-sm text-brand-900 placeholder:text-brand-400"
                />
                <button
                    @click="close"
                    class="text-brand-400 hover:text-brand-700 transition-colors shrink-0"
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
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Results -->
            <div v-if="query.length >= 2" class="max-h-72 overflow-y-auto p-2">
                <div
                    v-if="loading"
                    class="py-5 text-center text-xs text-brand-500"
                >
                    Searching...
                </div>

                <div
                    v-else-if="results.length === 0"
                    class="py-5 text-center text-xs text-brand-500"
                >
                    No products found
                </div>

                <button
                    v-for="product in results"
                    :key="product.id"
                    @click="goToProduct(product.slug)"
                    class="w-full flex items-center gap-2.5 px-2 py-2 hover:bg-brand-50/50 transition-colors text-left rounded-md"
                >
                    <div
                        class="w-10 h-10 bg-brand-50/60 rounded-md overflow-hidden shrink-0"
                    >
                        <img
                            v-if="product.images?.length"
                            :src="`/storage/${product.images[0]}`"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-brand-900 truncate">
                            {{ product.product_name }}
                        </p>
                        <p class="text-[11px] text-brand-500">
                            {{ product.category?.name }}
                        </p>
                    </div>
                    <p class="text-xs font-semibold text-brand-700 shrink-0">
                        {{
                            product.variants?.length
                                ? Math.min(
                                      ...product.variants.map((v) =>
                                          Number(v.price),
                                      ),
                                  )
                                : "—"
                        }}
                        EGP
                    </p>
                </button>
            </div>
        </div>
    </Transition>
</template>
