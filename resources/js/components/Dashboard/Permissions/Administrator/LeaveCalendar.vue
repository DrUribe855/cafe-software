<script setup>

const props = defineProps({
    weekDays: Array,
    filteredRequests: Array,
});

const emits = defineEmits(['openModal']);
</script>

<template>
    <section >
        <div class="p-4">
            <!-- Titulo del calendario (Mes y año) -->
            <h2 class="text-xl font-bold mb-4">
                test
            </h2>
            <div class="rounded-sm overflow-hidden">
                 <!-- Días de la semana (encabezado) -->
                <div class="grid grid-cols-7">
                    <div
                        v-for="(day, index) in weekDays"
                        :key="index"
                        class="font-bold text-center p-2 bg-gray-100 border-gray-300 border rounded-t-sm text-gray-500"
                    >
                        {{ day }}
                    </div>
                </div>

                <!-- Días del mes con solicitudes -->
                <div class="grid grid-cols-7">
                    <div
                        v-for="day in filteredRequests"
                        :key="day.date"
                        class="min-h-[120px] border p-1 border-gray-300"
                    >
                        <template v-if="day">
                            <div class="text-sm font-bold mb-1 pl-2 pt-1">
                                {{  day.day }}
                            </div>

                            <!-- Ciclo de permisos/solicitudes -->
                            <div class="px-1 mt-1">
                                <div
                                    v-for="request in day.requests"
                                    @click="$emit('openModal', request)"
                                    :key="request.id"
                                    class="text-xs rounded mb-1 flex p-1 border-l-4 bg-white"
                                    :class="{
                                        'border-orange-500 text-orange-600': request.status === 'Pendiente',
                                        'border-green-500 text-green-600': request.status === 'Aprobado',
                                        'border-red-500 text-red-600': request.status === 'Rechazado'
                                    }"
                                >
                                    <div class="leading-tight">
                                        {{ request.user.name }}<br>
                                        <span class="text-gray-500">{{ request.status }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ciclo de ausencias -->
                            <div class="px-1 mt-1">
                                <div
                                    v-for="absence in day.absences"
                                    :key="absence.id"
                                    class="text-xs rounded mb-1 flex p-1 border-l-4 bg-white"
                                    :class="{
                                        'border-orange-500 text-orange-600': absence.status === 'Pendiente',
                                        'border-green-500 text-green-600': absence.status === 'Aprobado',
                                        'border-red-500 text-red-600': absence.status === 'Rechazado'
                                    }"
                                >
                                    <div class="leading-tight">
                                        {{ absence.user.name }}<br>
                                        <span class="text-gray-500"> Ausente </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
