<script setup>

const props = defineProps({
    category: {
        type: Object,
        required: true,
    }
});

const emits = defineEmits(['save-category']);

</script>
<template>
    <div>
        <h2 class="text-base font-semibold text-slate-700 mb-3">
            Añadir nueva categoría
        </h2>

        <form v-if="props.category" @submit.prevent="emits('save-category')">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-slate-700">
                        Nombre de la categoría
                    </label>
                    <input
                        v-model="props.category.name"
                        class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    />
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-slate-700">
                        Descripción
                    </label>
                    <textarea
                        v-model="props.category.description"
                        class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none resize-none"
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-center">
                <button
                    class="mt-4 bg-sky-500 hover:bg-sky-400 text-white font-medium px-4 py-2 rounded transition"
                    type="submit"
                >
                    Crear categoría
                </button>
            </div>

        </form>

    </div>
</template>
