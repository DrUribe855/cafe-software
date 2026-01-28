<script setup>

import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useSuppliers } from '@/composables/Suppliers/useSuppliers';
import { useUserStore } from '../../../stores/userStore';
import { useSuppliersStore } from '@/stores/Suppliers/suppliersStore';
import InformationBar from './InformationBar.vue';
import UploaderFile from './UploaderImage.vue';
import ImageViewer from './ImageViewer.vue';

const store = useUserStore();
const { supplier, filteredImages, fetchSuppliers, fetchImages } = useSuppliers();
const supplierStore = useSuppliersStore();
const { getActualDate } = useDate();
const isReady = ref(false);
const role = ref(null);
const date = ref('');



onMounted(async () => {
    await store.fetchUser();
    await fetchSuppliers();
    role.value = store.user.role;
    if(role.value === 'admin'){
        await fetchImages();
    }
    isReady.value = true;
});


</script>
<template>
    <p v-if="!isReady">Cargando información...</p>
    <!-- Barra de navegación -->
    <template v-else>
        <InformationBar v-model:supplier="supplier"  v-model:date="date" :role="role" :suppliers="supplierStore.suppliers"/>
        <UploaderFile v-if="role == 'employee'" :supplier="supplier"/>
        <ImageViewer v-if="role == 'admin'" :date="date" :supplier="supplier" :logs="filteredImages"/>
    </template>

</template>
