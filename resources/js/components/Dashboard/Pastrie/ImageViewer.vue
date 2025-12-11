<script setup>
import { watch, computed } from 'vue';
import { useUploadImage } from '../../../composables/Pastrie/useUploadImage';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import ImageContainer from './ImageContainer.vue';

const props = defineProps({
    date: {
        String,
        required: true,
    },
    schedule: {
        String
    }
});

const { fetchImage, imageData } = useUploadImage();
const establishmentStore = useEstablishmentStore();

/* Vigila cambios en el código del establecimiento y la fecha seleccionada para cargar las imágenes */

watch( [() => establishmentStore.code, () => props.date], 
    ([newCode, newDate]) => {
        if(newCode && newDate){
            fetchImage(newDate);
        }
    },
    { immediate: true }
);

/* Función que retorna la información en base a si los valores están filtrados o no */

const filteredImages = computed(() => {

    if (!Array.isArray(imageData.value)) return [];

    if (!props.schedule) return imageData.value; // sin filtro

    return imageData.value.filter(img => img.schedule === props.schedule);
});

</script>



<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <article v-for="image in filteredImages" class="w-full">
            <ImageContainer
                :schedule="image.schedule"
                :date="image.created_at"
                :imageUrl="image.imageUrl"
                :username="image.username"
            />
        </article>
    </div>
</template>