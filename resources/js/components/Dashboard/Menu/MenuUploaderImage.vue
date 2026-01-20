<script setup>
import { ref } from "vue";
import { alert } from "@/composables/Pastrie/alert.js";

const emit = defineEmits(["selected"]);

const file = ref(null);
const isSelected = ref(false);

const fileSelected = (e) => {
  const selected = e.target.files[0];

  if (!selected) {
    file.value = null;
    isSelected.value = false;
    return;
  }

  if (!selected.type.startsWith("image/")) {
    alert("Archivo inválido", "Debes seleccionar una imagen.", "error");
    return;
  }

  file.value = selected;
  isSelected.value = true;

  emit("selected", file.value);
};
</script>

<template>
  <div class="flex flex-col items-center justify-center w-full mt-5">
    <label
      for="dropzone-file"
      class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 hover:border-blue-400"
    >
      <div class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg
          class="w-8 h-8 mb-4 text-gray-400"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 20 16"
        >
          <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
          />
        </svg>

        <p v-if="!isSelected" class="mb-2 text-sm text-gray-500">
          <span class="font-semibold text-blue-500">Selecciona una imagen</span>
        </p>
        <p class="text-xs text-gray-400">PNG, JPG, JPEG</p>
      </div>

      <input
        id="dropzone-file"
        type="file"
        accept="image/*"
        class="hidden"
        @change="fileSelected"
      />
    </label>

    <p v-if="isSelected" class="text-center mt-2 font-semibold text-green-500">
      Imagen seleccionada
    </p>
  </div>
</template>
