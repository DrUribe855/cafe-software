<script setup>
import { watch } from 'vue';
import { useUploadImage } from '../../../composables/Pastrie/useUploadImage';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import ImageContainer from './ImageContainer.vue';

const { date } = defineProps({
    date: {
        String,
        required: true,
    }
});

const { fetchImage, imageData } = useUploadImage();
const establishmentStore = useEstablishmentStore();

/* Vigila cambios en el código del establecimiento y la fecha seleccionada para cargar las imágenes */

watch( [() => establishmentStore.code, () => date], 
    ([newCode, newDate]) => {
        if(newCode && newDate){
            fetchImage(newDate);
        }
    },
    { immediate: true }
);

</script>



<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <article v-for="image in imageData.images" v-if="imageData.images" class="w-full">
            <ImageContainer
                :type="bolleria"
                :schedule="image.schedule"
                :date="image.created_at"
                :imageUrl="image.imageUrl"
                :username="image.username"
            />
        </article>
    </div>
</template>