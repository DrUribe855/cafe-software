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
    <div>
		<h2 class="my-6 text-2xl font-semibold text-gray-700">
			¡Bienvenido de nuevo, Paco!
		</h2>

		<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <a href="">
                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                    <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                        <component :is="props.icon" class="w-7 h-7" />
                    </div>
                    <div>
                        <p class="mb-2 text-medium font-bold text-gray-600">
                            {{ props.title }}
                        </p>
                        <p class="text-sm  text-gray-700">
                            {{  props.description }}
                        </p>
                    </div>
                </div>
            </a>
	    </div>
    </div>
</template>

