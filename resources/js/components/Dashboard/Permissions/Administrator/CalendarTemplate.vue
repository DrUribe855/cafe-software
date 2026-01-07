<script setup>
import { onMounted } from 'vue';
import SummaryCard   from './SummaryCard.vue';
import Loader        from '../../../Loader.vue';
import RequestCard   from './RequestCard.vue';
import LeaveFilters  from './LeaveFilters.vue';
import LeaveCalendar from './LeaveCalendar.vue';
import { Calendar, Clock, CircleCheck, CircleX } from 'lucide-vue-next';
import { useAdminLeaveRequests } from '../../../../composables/Leave/Administrator/useAdminLeaveRequests';
import { useDateFilter } from '../../../../composables/DateFilters/useDateFilter';
import { useLoader } from '../../../../composables/useLoader';
import { useLeaveRequestsStore } from '../../../../stores/LeaveRequests/Administrator/useLeaveRequestStore';
import { storeToRefs } from 'pinia';

const {
    fetchMonthData,
    weekDays,
    filteredRequests,
    employeeSearch,
    statusFilter,
    openRequestModal,
    closeRequestModal,
    selectedRequest,
    isModalOpen,
    sendRequestResponse
} = useAdminLeaveRequests();
const { currentDate, previousMonth, nextMonth } = useDateFilter();
const { isLoading } = useLoader();
const leaveRequestStore = useLeaveRequestsStore();
const { sumRequests } = storeToRefs(leaveRequestStore);

onMounted(() => {
    fetchMonthData(currentDate.value);
});

</script>


<template>
    <div class="p-6 bg-white min-h-screen m-4 mb-0 rounded-lg">
        <Loader v-if="isLoading" />

        <!-- Titulo y descripcion -->
        <h1 class="text-2xl font-bold mb-2">Gestión de Permisos</h1>
        <p class="text-gray-600 mb-6">Administra las solicitudes de vacaciones y permisos</p>

        <!-- Seccion de tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <SummaryCard title="Total Solicitudes" :icon="Calendar"    :value="parseInt(sumRequests.total)"    :type="1" />
          <SummaryCard title="Pendientes"        :icon="Clock"       :value="parseInt(sumRequests.pendings)" :type="2" />
          <SummaryCard title="Aprobadas"         :icon="CircleCheck" :value="parseInt(sumRequests.approved)" :type="3" />
          <SummaryCard title="Rechazadas"        :icon="CircleX"     :value="parseInt(sumRequests.rejected)" :type="4" />
        </div>

        <!-- Seccion de filtros -->
        <LeaveFilters
            v-model:search="employeeSearch"
            v-model:status="statusFilter"
            @prev="previousMonth"
            @next="nextMonth"
        />

        <!-- Seccion de calendario -->
        <LeaveCalendar
            :weekDays="weekDays"
            :filteredRequests="filteredRequests"
            :date="currentDate"
            @openModal="openRequestModal"
        />
    </div>

    <!-- Modal para visualización de información de la petición -->
    <Transition name="request-modal">
        <RequestCard
            v-if="isModalOpen"
            :request="selectedRequest"
            @close="closeRequestModal"
            @sendResponse="sendRequestResponse"
        />
    </Transition>

</template>

<style setup>

    .request-modal-enter-active,
    .request-modal-leave-active {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .request-modal-enter-from,
    .request-modal-leave-to {
        opacity: 0;
        transform: scale(0.95);
    }
</style>
