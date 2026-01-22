<script setup>
import { Trash } from "lucide-vue-next";

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    }
});

const emits = defineEmits(['delete-category']);

</script>

<template>
    <div class="bg-white p-5 rounded-lg shadow">
        <h2 class="font-bold text-lg mb-4 border-b pb-2">
            Categorías creadas
        </h2>

        <div v-if="!props.categories.length" class="text-gray-500 italic">
            No hay categorías creadas.
        </div>

        <div class="grid gap-4 max-h-[520px] overflow-y-auto pr-1">
            <div
                v-for="category in props.categories"
                :key="category.id"
                class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                    hover:shadow-md hover:border-sky-400 transition"
            >
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">
                            {{ category.name }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ category.description }}
                        </p>
                    </div>

                    <button
                        @click="emits('delete-category', category.id)"
                        class="p-1 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition"
                    >
                        <Trash class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
