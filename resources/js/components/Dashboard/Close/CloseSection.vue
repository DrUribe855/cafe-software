<script setup>
import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useUserStore } from '../../../stores/userStore';
import CloseBar from './CloseBar.vue'; 
import CloseUploaderImage from './CloseUploaderImage.vue'; 
import CloseImageViewer from './CloseImageViewer.vue'; 


const store = useUserStore();
const { getActualDate } = useDate();

const isReady = ref(false);
const role = ref(null);
const closeDate = ref('');
const selectedFridge = ref(null); 
const finalTemperature = ref('');

onMounted(async () => {
  await store.fetchUser();
  role.value = store.user.role;   
  closeDate.value = getActualDate();
  isReady.value = true;
});
</script>

<template>
  <p v-if="!isReady">Cargando información...</p>

  <template v-else>
    <!-- Barra superio-->
    <CloseBar
      v-model:date="closeDate"
      v-model:fridge="selectedFridge"
      v-model:temperature="finalTemperature"
      :role="role"
    />
    
    <!-- Empleado -->
    <CloseUploaderImage
      v-if="role === 'employee' && selectedFridge"
      :date="closeDate"
      :refrigerator-id="selectedFridge"
      :temperature="finalTemperature"
    />

    <!-- Admin -->
    <CloseImageViewer
      v-if="role === 'admin' && selectedFridge"
      :date="closeDate"
      :refrigerator-id="selectedFridge"
    />
  </template>
</template>
