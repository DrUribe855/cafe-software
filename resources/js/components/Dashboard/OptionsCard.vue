<script setup>

const props = defineProps({
    icon: {
        required: true
    },
    title: {
        required: true
    },
    description: {
        required: true
    }
});

</script>


<template>
	<div class="gap-6 mb-8">
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
	</div>
</template>

