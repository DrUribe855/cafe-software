<script setup>
import { computed } from 'vue';

const props = defineProps({
    schedule: { type: String, required: true },
    date: { type: String, required: true },
    imageUrl: { type: String, required: true },
    username: { type: String, required: true },
    temperature: { type: [String, Number], required: false, default: null }
});

const displayTemperature = computed(() => {
    if (props.temperature === null || props.temperature === undefined || props.temperature === '') {
        return 'No registrada';
    }
    return props.temperature;
});
</script>

<template>
  <div class="max-w-2xl w-full bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="bg-blue-500 px-4 py-2">
      <h2 class="text-white font-bold text-lg">Horario: {{ schedule }}</h2>
    </div>

    <div class="px-4 py-3">
      <p class="text-slate-900 font-semibold">{{ username }}</p>
      <p class="text-gray-500 text-sm">{{ date }}</p>
      <p class="text-gray-500 text-sm">Temperatura: {{ displayTemperature }} °C</p>
    </div>

    <div class="w-full flex justify-center p-4">
      <img
        :src="imageUrl"
        alt="Imagen de horario"
        class="max-w-full h-auto max-h-[25em] object-contain rounded-lg"
      />
    </div>
  </div>
</template>
