<script setup>

import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';

const date = ref();
const { getActualDate } = useDate();

onMounted( () => {
    date.value = getActualDate();
})



</script>


<template>
    <section class="grid grid-cols-2 items-center">
        <!-- Columna izquierda -->
        <div>
            <h1 class="text-lg font-semibold text-slate-800">Gestión de bollería</h1>
        </div>

        <!-- Columna derecha -->
        <div class="flex justify-end items-center gap-x-4">
            <label class="text-md font-semibold text-slate-800">Seleccione la fecha</label>
            <input
            type="date"
            v-model="date"
            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
    </section>
</template>
