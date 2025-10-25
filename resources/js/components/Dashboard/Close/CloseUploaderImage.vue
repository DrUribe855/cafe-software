<script setup>
import { ref } from 'vue';
import { useUploadClose } from '../../../composables/Pastrie/useUploadClose';
import { alert } from '../../../composables/Pastrie/alert';
import Swal from 'sweetalert2';

const props = defineProps({
  refrigeratorId: {
    type: Number,
    required: true
  },
  date: {
    type: String,
    required: true
  },
  temperature: {
    type: [String, Number],
    required: true
  }
});

const file = ref(null);
const isSelected = ref(false);
const { uploadCloseFiles } = useUploadClose();

const fileSelected = (event) => {
  file.value = event.target.files[0];
  isSelected.value = true;
  console.log("Archivo seleccionado:", file.value);
};

const submitFile = async () => {
  if (!file.value) {
    alert("Debes seleccionar un archivo antes de subir", "", "warning");
    return;
  }

  const temp = Number(props.temperature);
  if (isNaN(temp) || props.temperature === '') {
      alert("Debes ingresar la temperatura final antes de subir", "", "warning");
    return;
  }

  try {
    const result = await uploadCloseFiles(
      [file.value],
      props.refrigeratorId,
      temp
    );
    console.log("Archivo subido correctamente:", result);
    file.value = null;
    isSelected.value = false;
  } catch (err) {
    console.error("Error al subir el archivo:", err.response?.data || err);
    alert("Ocurrió un error al subir el cierre. Inténtalo nuevamente.");
  }
};
</script>

<template>
  <form @submit.prevent="submitFile">
    <div class="flex items-center justify-center w-full mt-5">
      <label
        for="dropzone-file"
        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 hover:border-blue-400"
      >
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
          <svg
            class="w-8 h-8 mb-4 text-gray-400 hover:text-blue-500 transition-colors"
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
          <p class="mb-2 text-sm text-gray-500">
            <span class="font-semibold text-blue-500">Selecciona para subir la imagen</span>
          </p>
          <p class="text-xs text-gray-400">PNG, JPG o JPEG</p>
        </div>
        <input id="dropzone-file" type="file" class="hidden" @change="fileSelected" />
        <p v-if="isSelected" class="font-semibold text-green-500">Archivo seleccionado con éxito</p>
      </label>
    </div>

    <div class="text-center mt-4">
      <button class="p-2.5 bg-sky-400 rounded text-white hover:bg-sky-500" type="submit">
        Subir imagen
      </button>
    </div>
  </form>
</template>
