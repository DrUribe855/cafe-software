<script setup>
import { watch, onMounted } from 'vue';
import { useUploadClose } from '../../../composables/Pastrie/useUploadClose';
import ImageContainer from '../Pastrie/ImageContainer.vue';
import { useEstablishmentStore } from '../../../stores/establishmentStore';

const props = defineProps({
  date: { type: String, required: true },
  refrigeratorId: { type: [String, Number], default: null }
});

const establishmentStore = useEstablishmentStore();
const { fetchCloseFiles, closeData } = useUploadClose();

onMounted(() => {
  if (!establishmentStore.refrigeratorsLoaded) {
    establishmentStore.fetchRefrigerators();
  }
});

const loadCloseFiles = () => {
  if (!props.date) return;
  fetchCloseFiles(props.date, props.refrigeratorId || null);
};

watch(
  () => [props.date, props.refrigeratorId, establishmentStore.getCode()],
  () => loadCloseFiles(),
  { immediate: true }
);

const getFridgeName = (id) => {
  if (!id) return "Todas";
  const fridge = establishmentStore.refrigerators.find(r => r.id == id);
  return fridge ? fridge.name : "Desconocida";
};
</script>

<template>
  <div v-if="closeData.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <article
      v-for="image in closeData"
      :key="image.id"
      class="w-full"
    >
      <ImageContainer
        :type="'cierre'"
        :date="image.created_at"
        :imageUrl="image.image_url"
        :username="image.user?.name"
        :schedule="image.schedule || 'Cierre'"
        :temperature="image.temperature"
        :fridgeName="image.refrigerator?.name || 'Desconocida'"
      />
    </article>
  </div>

  <p v-else class="text-center text-gray-500 mt-6">
    No hay imágenes registradas para esta fecha.
  </p>
</template>
