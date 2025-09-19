<script setup>

const props = defineProps({
    schedule: {
        String,
        required: true,
    },
    date: {
        String,
        required: true,
    },
    imageUrl: {
        String,
        required: true,
    },
    username: {
        String,
        required: true,
    }
});

</script>

<template>
    <div class="max-w-2xl w-full bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <!-- Cabecera -->
        <div class="bg-blue-500 px-4 py-2">
            <h2 class="text-white font-bold text-lg">Horario: {{ schedule }}</h2>
        </div>

        <!-- Info -->
        <div class="px-4 py-3">
            <p class="text-slate-900 font-semibold">{{ username }}</p>
            <p class="text-gray-500 text-sm">{{ date }}</p>
        </div>

        <!-- Imagen -->
        <div class="w-full flex justify-center p-4">
            <img
                :src="imageUrl"
                alt="Imagen de horario"
                class="max-w-full h-auto max-h-[25em] object-contain rounded-lg"
            />
        </div>
    </div>
</template>
