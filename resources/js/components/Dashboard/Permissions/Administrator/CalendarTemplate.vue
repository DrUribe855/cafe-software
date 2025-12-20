<script setup>
import SummaryCard from './SummaryCard.vue';
import { Calendar, Clock, CircleCheck, CircleX } from 'lucide-vue-next';
import { useLeaveRequests } from '../../../../composables/Leave/useLeaveRequests';
import { useDateFilter } from '../../../../composables/DateFilters/useDateFilter';
import { onMounted } from 'vue';

const { fetchMonthData } = useLeaveRequests();
const { currentDate, nextWeek, previousWeek } = useDateFilter();

onMounted(() => {
    fetchMonthData(currentDate.value);
});

</script>


<template>
    <div class="p-6 bg-white min-h-screen m-4 mb-0 rounded-lg">

        <!-- Titulo y descripcion -->
        <h1 class="text-2xl font-bold mb-2">Gestión de Permisos</h1>
        <p class="text-gray-600 mb-6">Administra las solicitudes de vacaciones y permisos</p>

        <!-- Seccion de tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <SummaryCard title="Total Solicitudes" :icon="Calendar"    :value="6" :type="1" />
          <SummaryCard title="Pendientes"        :icon="Clock"       :value="3" :type="2" />
          <SummaryCard title="Aprobadas"         :icon="CircleCheck" :value="2" :type="3" />
          <SummaryCard title="Rechazadas"        :icon="CircleX"     :value="1" :type="4" />
        </div>

        <!-- Seccion de filtros -->
        <div class="flex flex-wrap gap-3 mb-6 bg-white border-t border-gray-200 shadow-md rounded-lg p-3">
            <input 
                type="text" 
                placeholder="Buscar empleado..." 
                class="w-full md:w-1/3 lg:w-1/5 px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300" 
            />
            <select class="w-full md:w-1/3 lg:w-1/5 px-3 py-2 rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-300">
                <option selected value="">Estado de permiso</option>
                <option>Aprobado</option>
                <option>Pendiente</option>
                <option>Rechazado</option>
            </select>
        </div>

        <!-- Seccion de calendario -->
        <button @click="previousWeek()">Anterior</button>
        <button @click="nextWeek()">Siguiente</button>

    </div>

</template>