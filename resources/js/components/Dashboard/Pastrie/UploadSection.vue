<script setup>

import { ref, onMounted, computed } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useUserStore } from '../../../stores/userStore';
import { useRole } from '../../../composables/useRole';
import InformationBar from './InformationBar.vue';
import UploaderFile from './UploaderImage.vue';
import ImageViewer from './ImageViewer.vue';

const store = useUserStore();


const schedule = ref('');
const date = ref('');
const { getActualDate } = useDate();
const role = ref(null);


onMounted(async () => {
    await store.fetchUser();
    role.value = store.user.role;
    date.value = getActualDate();
});


</script>
<template>
    <!-- <p v-if="store.user.isLoading">⏳ Cargando usuario...</p> -->
    <!-- Barra de navegación -->
    <InformationBar v-model:schedule="schedule"  v-model:date="date" :role="role"/>
    <UploaderFile v-if="role == 'employee'" :schedule="schedule"/>
    <ImageViewer v-if="role == 'admin'" :date="date"/>

</template>
