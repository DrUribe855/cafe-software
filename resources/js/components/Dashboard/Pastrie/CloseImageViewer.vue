<script setup>
import { watch } from 'vue';
import { useUploadImage } from '../../../composables/Pastrie/useUploadImage';
import ImageContainer from './ImageContainer.vue';

const props = defineProps({
  date: {
    type: String,
    required: true,
  }
});

const { fetchImage, imageData } = useUploadImage();


watch(
  () => props.date,
  (newDate) => {
    if (newDate) {
      fetchImage(newDate);
    }
  },
  { immediate: true }
);
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <article
      v-if="imageData && imageData.images"
      v-for="image in imageData.images"
      :key="image.id"
      class="w-full"
    >
      <ImageContainer
        :schedule="image.schedule"
        :date="image.created_at"
        :imageUrl="image.imageUrl"
        :username="image.username"
      />
    </article>
  </div>
</template>
