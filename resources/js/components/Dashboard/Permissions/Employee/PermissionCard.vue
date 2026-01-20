<script setup>
    import { CircleCheck, Clock, CircleX  } from "lucide-vue-next";


    const props = defineProps({
        type:       String,
        start_date: String,
        end_date:   String,
        reason:     String,
        status:     String,
        comments:   String,
    });

    const icons = {
        Pendiente: Clock,
        Aprobado:  CircleCheck,
        Rechazado: CircleX,
    };

    const statusClasses = {
        Pendiente: 'bg-yellow-50 text-yellow-700 border-yellow-500',
        Aprobado:  'bg-green-50 text-green-700 border-green-500',
        Rechazado: 'bg-red-50 text-red-700 border-red-500',
    };

</script>

<template>
    <div class="p-4 rounded-xl border flex justify-between items-start m-4"
        :class="statusClasses[status]"
    >

        <!-- Información -->
        <div>
            <!-- Título + Estado -->
            <div class = "flex items-center gap-3">
                <h2 class = "text-sm md:text-md sm:text-sm font-semibold">{{  type  }}</h2>

                <span
                    class = "px-3 py-1 text-sm rounded-full font-medium flex items-center gap-1"
                    :class = "{
                            'bg-orange-100': status === 'Pendiente',
                            'bg-green-200' : status === 'Aprobado',
                            'bg-red-100'   : status === 'Rechazado'
                        }"
                >
                    <span class="inline-block w-2 h-2 rounded-full"
                        :class = "{
                            'bg-yellow-500': status === 'Pendiente',
                            'bg-green-500' : status === 'Aprobado',
                            'bg-red-500'   : status === 'Rechazado'
                        }">
                    </span>
                    {{ status }}
                </span>
            </div>

            <!-- Fechas -->
            <p class = "text-gray-600 mt-2 text-sm md:text-md">
                {{ start_date }} / {{ end_date }}
            </p>

            <!-- Descripción -->
            <p class = "text-gray-700 mt-1 text-sm md:text-md">
                {{ reason }}
            </p>

            <p v-if="comments" class = "text-gray-700 mt-1 text-sm md:text-md">
                <b>Respuesta dada</b>: {{ comments }}
            </p>
        </div>

        <!-- Ícono -->
        <component
            :is="icons[status]"
        />

    </div>
</template>
