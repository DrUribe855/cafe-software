<script setup>
import { computed } from 'vue';

const props = defineProps({
    supplier: { type: String, required: true },
    date: { type: String, required: true },
    imageUrl: { type: String, required: true },
    username: { type: String, required: true },
});


</script>

<template>
  <div class="max-w-2xl w-full bg-white rounded-xl shadow-md overflow-hidden mb-6">

    <!-- HEADER -->
    <div class="bg-blue-500 px-4 py-2">
      <h2 class="text-white font-bold text-lg">Proveedor: {{ supplier }}</h2>
    </div>

    <div class="px-4 py-3 space-y-1">
      <p class="text-slate-900 font-semibold">Recibido por: {{ username }}</p>
      <p class="text-gray-500 text-sm">Fecha: {{ date }}</p>
    </div>

    <!-- IMAGEN -->
    <div class="w-full flex justify-center p-4">
      <img
        :src="imageUrl"
        alt="Imagen subida"
        class="max-w-full h-auto max-h-[25em] object-contain rounded-lg"
      />
    </div>

  </div>
</template>
