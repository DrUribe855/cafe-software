<script setup>
    import { CircleCheck, Clock, CircleX  } from "lucide-vue-next";


    const props = defineProps({
        type: String,
        startDate: String,
        endDate: String,
        reason: String,
        status: String,
    });

    const icons = {
        Pendiente: Clock,
        Aprobado: CircleCheck,
        Rechazado: CircleX,
    };

    const statusClasses = {
        Pendiente: 'bg-yellow-50 text-yellow-700 border-yellow-500',
        Aprobado: 'bg-green-50 text-green-700 border-green-500',
        Rechazado: 'bg-red-50 text-red-700 border-red-500',
    };

    const iconClasses = {
        Pendiente: 'text-yellow-600',
        Aprobado: 'text-green-600',
        Rechazado: 'text-red-600',
    };
</script>

<template>
    <div class="p-4 rounded-xl border flex justify-between items-start m-4"
        :class="statusClasses[status]"
    >

        <!-- Información -->
        <div>
            <!-- Título + Estado -->
            <div class="flex items-center gap-3">
                <h2 class="text-lg md:text-md sm:text-sm font-semibold">{{  type  }}</h2>

                <span
                    class="px-3 py-1 text-sm rounded-full font-medium flex items-center gap-1"
                    :class="statusClasses[status]"
                >
                    <span class="inline-block w-2 h-2 rounded-full"
                        :class="{
                            'bg-yellow-500': status === 'Pendiente',
                            'bg-green-500': status === 'Aprobado',
                            'bg-red-500': status === 'Rechazado'
                        }">
                    </span>
                    {{ status }}
                </span>
            </div>

            <!-- Fechas -->
            <p class="text-gray-600 mt-2">
                {{ startDate }} / {{ endDate }}
            </p>

            <!-- Descripción -->
            <p class="text-gray-700 mt-1">
                {{ reason }}
            </p>
        </div>

        <!-- Ícono -->
        <component
            :is="icons[status]"

        />

    </div>
</template>
