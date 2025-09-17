<template>
  <div class="flex flex-col items-center justify-center p-4">
    <div v-if="loading" class="text-gray-500">Cargando imagen...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>
    <div v-else>
      <img
        :src="imageSrc"
        alt="Imagen subida"
        class="max-w-md rounded-xl shadow-lg border"
      />
    </div>
  </div>
</template>
