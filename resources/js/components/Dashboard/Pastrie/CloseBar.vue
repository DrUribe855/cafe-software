<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  date: String,
  role: String,
  observations: String
});

const emit = defineEmits(['update:date', 'update:observations']);

const router = useRouter();

const goBack = () => {
  router.back();
};
</script>

<template>
  <!-- Botón volver -->
    <button @click="goBack" class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        <!-- Icono para dispositivos móviles -->
        <span class="hidden md:inline text-sm font-medium">Volver</span>
    </button>

  <!-- Sección principal -->
  <section
    class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 p-4 mt-3 bg-white shadow-sm rounded-lg mb-4"
  >
    <!-- Columna izquierda -->
    <h1
      class="text-lg md:text-xl font-bold text-slate-800 border-l-4 border-sky-400 pl-3"
    >
      Gestión de cierre
    </h1>

    <!-- Columna derecha -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
      <!-- Observaciones para temperaturas -->
      <div v-if="role === 'employee'" class="flex flex-col gap-2 w-full">
        <label class="text-sm font-semibold text-slate-700">
          Temperaturas:
        </label>
        <textarea
          :value="observations"
          @input="e => emit('update:observations', e.target.value)"
          placeholder="Temperaturas del cierre..."
          class="w-full sm:w-[20rem] p-2 border border-gray-300 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
          rows="3"
        ></textarea>
      </div>

      
      <!-- Date para admin -->
      <div
        v-if="role === 'admin'"
        class="flex flex-col sm:flex-row sm:items-center gap-2"
      >
        <label class="text-base font-semibold text-slate-700">
          Fecha:
        </label>
        <input
          type="date"
          class="w-full sm:w-auto p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
          :value="date"
          @change="e => emit('update:date', e.target.value)"
        />
      </div>
    </div>
  </section>
</template>
