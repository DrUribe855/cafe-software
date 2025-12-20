<script setup>

const props = defineProps({
    title: String,
    value: Number,
    type:  Number,
    icon:  Function,
});

/* Valores de type:

    1 = Todas las solicitudes
    2 = Pendientes
    3 = Aprobadas
    4 = Rechazadas

*/

</script>



<template>
  <div class="p-4 rounded-lg border-t border-gray-200 shadow-md">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-sm lg:text-lg font-medium text-gray-600">{{ title }}</h3>
            <p class="text-2xl font-bold mt-1 ml-1">{{ value }}</p>
        </div>
        <div 
            class="rounded-full p-3 mr-2"
            :class=" {
                'bg-gray-100'  : type === 1,
                'bg-orange-100': type === 2,
                'bg-green-100' : type === 3,
                'bg-red-100'   : type === 4
            }"
        >
            <component 
                :is="icon" 
                class="w-8 h-8 lg:w-9 lg:h-9"
                :class=" {
                    'text-gray-500'  : type === 1,
                    'text-orange-500': type === 2,
                    'text-green-500' : type === 3,
                    'text-red-500'   : type === 4
                }" 
            />
        </div>
    </div>
  </div>
</template>
