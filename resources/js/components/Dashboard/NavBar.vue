<script setup lang="ts">

const props = defineProps({
    name: {
        type: String,
        required: true
    }
})


</script>


<template>
  <nav class="flex items-center h-15 px-4 bg-white shadow-xs mb-5">
    <!-- Primer item a la izquierda -->
    <div class="flex-shrink-0">
      <p class="font-semibold text-1xl">¡Bienvenido {{ props.name }}!</p>
    </div>

    <!-- Espaciador para empujar lo demás a la derecha -->
    <div class="flex-1"></div>

    <!-- Items a la derecha -->
    <div class="flex items-center gap-4">
      <select class="border rounded px-2 py-1" name="" id="">
        <option value="">Tienda 1</option>
        <option value="">Tienda 2</option>
      </select>
      <button class="bg-red-500 text-white px-3 py-1 rounded">Cerrar sesión</button>
    </div>
  </nav>
</template>
