<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  date: String,
  role: String,
  fridge: Number,
  temperature: [String, Number] // prop que recibimos desde el padre
});

const emit = defineEmits(['update:date', 'update:fridge', 'update:temperature']);

const router = useRouter();

// Nevera seleccionada y temperatura 
const selectedFridge = ref(props.fridge || '');
const localTemp = ref(props.temperature || '');

// Lista de neveras
const fridges = [
  { value: 1, label: 'Nevera 1' },
  { value: 2, label: 'Nevera 2' },
  { value: 3, label: 'Nevera 3' },
  { value: 4, label: 'Nevera 4' },
  { value: 5, label: 'Nevera 5' },
  { value: 6, label: 'Nevera 6' },
  { value: 7, label: 'Nevera 7' }
];

watch(selectedFridge, val => emit('update:fridge', val));
watch(localTemp, val => emit('update:temperature', val));

const goBack = () => {
  router.back();
};
</script>

<template>
  <!-- Botón volver -->
  <button
    @click="goBack"
    class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition"
  >
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="2" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
    <span class="hidden md:inline text-sm font-medium">Volver</span>
  </button>

  <!-- Sección principal -->
  <section
    class="flex flex-col md:flex-row md:justify-between gap-4 p-4 mt-3 bg-white shadow-sm rounded-lg mb-4"
  >
    <!-- Columna izquierda -->
    <h1 class="text-lg md:text-xl font-bold text-slate-800 border-l-4 border-sky-400 pl-3">
      Gestión de cierre
    </h1>

    <!-- Columna derecha -->
    <div class="flex flex-col gap-4 items-start md:items-end w-full md:w-auto">
      <!-- Fecha -->
      <div v-if="role === 'admin'" class="flex flex-col sm:flex-row sm:items-center gap-2">
        <label class="text-base font-semibold text-slate-700">Fecha:</label>
        <input
          type="date"
          class="w-full sm:w-auto p-2 border border-gray-300 rounded-lg shadow-sm 
                 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
          :value="date"
          @change="e => emit('update:date', e.target.value)"
        />
      </div>

      <!-- Select de neveras -->
      <div class="flex flex-col gap-2 w-full sm:w-48">
        <label class="text-sm font-semibold text-slate-700 text-left md:text-right">
          Seleccionar nevera:
        </label>
        <select
          v-model="selectedFridge"
          class="w-full p-2 border border-gray-300 rounded-lg shadow-sm 
                 focus:outline-none focus:ring-2 focus:ring-sky-500 
                 hover:border-sky-400 transition"
        >
          <option disabled value="">Seleccionar nevera</option>
          <option v-for="fridge in fridges" :key="fridge.value" :value="fridge.value">
            {{ fridge.label }}
          </option>
        </select>

        <!-- Input de temperatura -->
        <div v-if="role === 'employee'" class="flex flex-col gap-2 w-full">
          <label class="text-sm font-semibold text-slate-700 text-left md:text-right">
            Temperatura final (°C):
          </label>
          <input
            type="number"
            v-model="localTemp"
            placeholder="Temperatura en °C"
            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-sky-500 hover:border-sky-400 transition"
          />
        </div>
      </div>
    </div>
  </section>
</template>
