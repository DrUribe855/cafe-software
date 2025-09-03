<script setup lang="ts">
import type { Component } from 'vue';

const props = defineProps({
  icon: {
    type: Object as () => Component,
    required: true
  },
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  }
});
</script>

<template>
  <div
    class="flex items-center sm:flex-col sm:items-start p-4 bg-white rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-200 cursor-pointer"
  >
    <!-- Icono-->
    <div
      class="p-3 mr-4 sm:mr-0 sm:mb-3 text-sky-500 bg-sky-100 rounded-full">
       <component :is="props.icon" class="w-7 h-7 md:w-8 md:h-8 animate-pulse md:animate-none" />
    </div>

    <!-- Texto -->
    <div>
      <p class="mb-1 text-lg font-semibold text-gray-800">
        {{ props.title }}
      </p>
      <p class="text-sm text-gray-600 leading-snug">
        {{ props.description }}
      </p>
    </div>
  </div>
</template>

