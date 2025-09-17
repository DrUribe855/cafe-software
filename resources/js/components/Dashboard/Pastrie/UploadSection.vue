<script setup>

import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useUserStore } from '../../../stores/userStore';
import InformationBar from './InformationBar.vue';
import UploaderFile from './UploaderImage.vue';

const store = useUserStore();
const { user } = store;


const schedule = ref('');
const date = ref('');
const { getActualDate } = useDate();




onMounted( () => {
    date.value = getActualDate();
});


</script>
<template>
    <!-- Barra de navegación -->
    <InformationBar v-model:schedule="schedule"  v-model:date="date" :role="user.role"/>
    <UploaderFile :schedule="schedule"/>


</template>
