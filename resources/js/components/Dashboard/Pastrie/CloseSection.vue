<script setup>
import { ref, onMounted } from 'vue';
import { useDate } from '../../../composables/useDate';
import { useUserStore } from '../../../stores/userStore';
import CloseBar from './CloseBar.vue'; 
import CloseUploaderFile from './CloseUploaderImage.vue'; 
import CloseImageViewer from './CloseImageViewer.vue'; 

const store = useUserStore();
const { getActualDate } = useDate();

const isReady = ref(false);
const role = ref(null);
const closeDate = ref('');
const observations = ref(''); 

onMounted(async () => {
  await store.fetchUser();
  role.value = store.user.role;   
  isReady.value = true;
  closeDate.value = getActualDate();
});
</script>

<template>
  <p v-if="!isReady">Cargando información...</p>
  <template v-else>
    <!-- Barra superior -->
    <CloseBar
      v-model:date="closeDate"
      v-model:observations="observations"
      :role="role"
    />
    
    <!-- Roles -->
    <CloseUploaderFile
      v-if="role === 'employee'"
      :date="closeDate"
      :observations="observations"
    />
    
    <CloseImageViewer
      v-if="role === 'admin'"
      :date="closeDate"
    />
  </template>
</template>
