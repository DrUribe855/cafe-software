<script setup>
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    search: String,
    status: String,
});

/* Definimos las variables y funciones que se emitirán al componente padre */

const emits = defineEmits(['update:search', 'update:status', 'prev', 'next']);

</script>

<template>
    <div class="flex flex-wrap gap-3 mb-6 bg-white border-t border-gray-200 shadow-md rounded-lg p-3">
        <div class="px-3 py-2 flex gap-2">
            <ChevronLeft  @click="$emit('prev', 1)" />
            <ChevronRight @click="$emit('next', 1)" />
        </div>
        <input
            type="text"
            placeholder="Buscar empleado..."
            :value="search"
            @input="$emit('update:search', $event.target.value)"
            class="w-full md:w-1/3 lg:w-1/5 px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300"
        />
        <select
            :value="status"
            @change="$emit('update:status', $event.target.value)"
            class="w-full md:w-1/3 lg:w-1/5 px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300"
        >
            <option selected value="">Todos</option>
            <option>Aprobado</option>
            <option>Pendiente</option>
            <option>Rechazado</option>
        </select>
    </div>
</template>
