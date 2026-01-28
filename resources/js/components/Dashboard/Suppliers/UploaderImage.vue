<script setup>
// import { useUploadImage } from '../../../composables/Pastrie/useUploadImage';
import { useLoader } from '../../../composables/useLoader.js';
import { useSuppliers } from '@/composables/Suppliers/useSuppliers.js';
import Loader from '../../Loader.vue';

const props = defineProps({
    supplier: {
        type: String,
        required: true
    },
});

const { images, uploadImage, fileSelected, removeImage } = useSuppliers();
// const { images, uploadImage, removeImage, fileSelected } = useUploadImage();
const { isLoading } = useLoader();

</script>

<template>

    <!-- Loader -->
    <Loader v-if="isLoading" />

    <!-- Dropzone para subida de imagenes -->
    <form @submit.prevent="uploadImage(props.supplier)">
        <div class="flex items-center justify-center w-full mt-5">
            <label
                for="dropzone-file"
                class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 hover:border-blue-400 p-4"
            >
                <!-- Estado inicial -->
                <div v-if="images.length === 0" class="flex flex-col items-center justify-center py-10">
                    <svg class="w-8 h-8 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>

                    <p class="mb-2 text-sm text-gray-500">
                        <span class="font-semibold text-blue-500">Selecciona imágenes</span>
                    </p>
                    <p class="text-xs text-gray-400">SVG, PNG o JPG</p>
                </div>

                <!-- Previews -->
                <div
                    v-else
                    class="w-full flex flex-col md:flex-row flex-wrap items-start justify-center gap-4 p-3"
                >
                    <div
                        v-for="(img, index) in images"
                        :key="index"
                        class="relative"
                    >
                        <!-- Imagen -->
                        <img
                            :src="img.preview"
                            class="rounded-lg object-contain max-h-60 md:max-h-72"
                        />

                        <!-- Botón de eliminar -->
                        <button
                            @click.stop.prevent="removeImage(index)"
                            class="absolute top-2 right-2 bg-white/80 hover:bg-red-500 hover:text-white text-red-500 rounded-full p-1 shadow transition-all"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <input id="dropzone-file" type="file" multiple class="hidden" @change="fileSelected($event)" />

             </label>
        </div>
        <div class="text-center mt-4 bg">
            <button class="p-2.5 bg-sky-500 rounded text-white hover:bg-sky-600" type="submit">Subir imagen</button>
        </div>
    </form>
</template>
