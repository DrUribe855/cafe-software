<script setup>

import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useUserStore } from '../../../stores/userStore';
import InformationBar from './InformationBar.vue';
import UploaderFile from './UploaderImage.vue';
import ImageViewer from './ImageViewer.vue';

const store = useUserStore();
const { getActualDate } = useDate();
const isReady = ref(false);
const role = ref(null);
const schedule = ref('');
const date = ref('');



onMounted(async () => {
    await store.fetchUser();
    role.value = store.user.role;
    isReady.value = true;
    date.value = getActualDate();
});


</script>
<template>
    <p v-if="!isReady">Cargando información...</p>
    <!-- Barra de navegación -->
    <template v-else>
        <InformationBar v-model:schedule="schedule"  v-model:date="date" :role="role"/>
        <UploaderFile v-if="role == 'employee'" :schedule="schedule"/>
        <ImageViewer v-if="role == 'admin'" :date="date" :schedule="schedule"/>
    </template>

</template>