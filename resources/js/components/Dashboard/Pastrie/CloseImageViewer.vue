<script setup>
import { watch } from 'vue';
import { useUploadClose } from '../../../composables/Pastrie/useUploadClose';
import ImageContainer from './ImageContainer.vue';

const props = defineProps({
  date: {
    type: String,
    required: true,
  },
  refrigeratorId: {
    type: [String, Number],
    required: true,
  }
});

const { fetchCloseFiles, closeData } = useUploadClose();

watch(
  () => [props.date, props.refrigeratorId],
  ([newDate, newFridge]) => {
    if (newDate && newFridge) {
      fetchCloseFiles(newDate, newFridge);
    }
  },
  { immediate: true }
);
</script>

<template>
  <div v-if="closeData.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <article
      v-for="image in closeData"
      :key="image.id"
      class="w-full"
    >
      <ImageContainer
        :date="image.created_at"
        :imageUrl="image.image_url"
        :username="image.user?.name"
      />
    </article>
  </div>

  <p v-else class="text-center text-gray-500 mt-6">
    No hay imágenes registradas para esta nevera en la fecha seleccionada.
  </p>
</template>
