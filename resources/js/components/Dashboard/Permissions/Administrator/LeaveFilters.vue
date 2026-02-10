<script setup>
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { useRouter } from  'vue-router';

const props = defineProps({
    search: String,
    status: String,
    role: String,
});

/* Definimos las variables y funciones que se emitirán al componente padre */

const emits = defineEmits(['update:search', 'update:status', 'prev', 'next']);
const router  = useRouter();

</script>

<template>
    <div class="flex flex-wrap items-center gap-3 mb-6 bg-white border-t border-gray-200 shadow-md rounded-lg p-3">
        <!-- Navigation arrows -->
        <div class="flex gap-2 px-3 py-2">
            <ChevronLeft  @click="$emit('prev', 1)" class="cursor-pointer" />
            <ChevronRight @click="$emit('next', 1)" class="cursor-pointer" />
        </div>
        
        <!-- Admin filters -->
        <div v-if="role === 'admin'" class="flex flex-wrap gap-3 w-full md:w-auto">
            <input
                type="text"
                placeholder="Buscar empleado..."
                :value="search"
                @input="$emit('update:search', $event.target.value)"
                class="w-full md:w-auto px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300"
            />
            <select
                :value="status"
                @change="$emit('update:status', $event.target.value)"
                class="w-full md:w-auto px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300"
            >
                <option selected value="">Todos</option>
                <option>Aprobado</option>
                <option>Pendiente</option>
                <option>Rechazado</option>
            </select>
        </div>
        
        <!-- Employee button -->
        <button 
            class="w-full md:w-auto md:ml-auto bg-sky-400 hover:bg-sky-500 transition-colors rounded-lg px-4 py-2 text-white font-medium" 
            @click="router.push('permissions-form')"
            v-if="role !== 'admin'"
        >
            Formulario de permisos
        </button>
    </div>
</template>
