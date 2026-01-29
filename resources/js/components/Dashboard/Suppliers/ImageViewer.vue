<script setup>
import { watch, onMounted } from 'vue';
import { useSuppliers } from '@/composables/Suppliers/useSuppliers';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import ImageContainer from './ImageContainer.vue';

const props = defineProps({
    date: {
        String,
        required: false,
    },
    supplier: {
        String,
        required: true,
    },
    logs: {
        Array,
        required: true,
    }
});
const { fetchImages } = useSuppliers();
// const { fetchImage, imageData } = useUploadImage();
const establishmentStore = useEstablishmentStore();

/* Vigila cambios en el código del establecimiento y la fecha seleccionada para cargar las imágenes */

watch( [() => establishmentStore.code, () => props.date],
    ([newCode, newDate]) => {

        if(!newCode) return null

        fetchImages(newDate || null);
    },
);

</script>



<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <article v-for="log in logs" class="w-full">
            <ImageContainer
                :supplier="log.supplier"
                :date="log.created_at"
                :imageUrl="log.imageUrl"
                :username="log.username"
            />
        </article>
    </div>
</template>
