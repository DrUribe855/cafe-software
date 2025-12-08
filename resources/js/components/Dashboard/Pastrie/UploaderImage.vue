<script setup>
import { ref } from 'vue';
import { useUploadImage } from '../../../composables/Pastrie/useUploadImage';
import { useLoader } from '../../../composables/useLoader.js';
import { alert } from '../../../composables/Pastrie/alert.js';

const props = defineProps({
    schedule: { type: String, required: true },
    // establishmentId: { type: Number, required: true },
    // userId: { type: Number, required: true },
});

const previewUrl = ref(null)

const handleFileChange = (event) => {
    const file = event.target.files[0]
    if (!file) return

    previewUrl.value = URL.createObjectURL(file)
}

const file = ref(null);
const isSelected = ref(false);
const { uploadImage } = useUploadImage();
const { isLoading } = useLoader();

const fileSelected = (event) => {
    file.value = event.target.files[0];
    isSelected.value = !!file.value;
    console.log("Archivo seleccionado:", file.value);
};

const submitForm = async () => {
    if (!file.value) {
        alert("Selecciona un archivo");
        return;
    }
    if (!props.schedule) {
        alert("No hay horario definido");
        return;
    }

    try{
        const response = await uploadImage(file.value, props.schedule);
        console.log('Respuesta de la subida en archivo UploaderImage.vue: ', response);
        file.value = null;
        isSelected.value = false;
    }catch(err){
        console.error("Error al subir la imagen en bollería: ", err.response.data);
        alert("Ocurrió un error al subir la imagen. Inténtalo nuevamente.");
    }

};
</script>

<template>
    <div v-if="isLoading"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="h-12 w-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
    </div>
    <form @submit.prevent="submitForm">
        <div class="flex items-center justify-center w-full mt-5">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 hover:border-blue-400">
                <div v-if="!previewUrl" class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg class="w-8 h-8 mb-4 text-gray-400 hover:text-blue-500 transition-colors"
             aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 20 16">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
        </svg>
        <p class="mb-2 text-sm text-gray-500">
            <span class="font-semibold text-blue-500">Selecciona para subir la imagen</span>
        </p>
        <p class="text-xs text-gray-400">SVG, PNG o JPG</p>
    </div>

    <!-- PREVIEW DENTRO DEL DROPZONE -->
    <div v-else class="w-full h-full flex items-center justify-center p-2">
        <img :src="previewUrl" class="h-full object-contain rounded-lg" />
    </div>

            <input id="dropzone-file" type="file" class="hidden" @change="fileSelected($event); handleFileChange($event);" />
            </label>
        </div>
        <div class="text-center mt-4 bg">
            <button class="p-2.5 bg-sky-400 rounded text-white hover:bg-sky-500" type="submit">Subir imagen</button>
        </div>
    </form>
</template>
