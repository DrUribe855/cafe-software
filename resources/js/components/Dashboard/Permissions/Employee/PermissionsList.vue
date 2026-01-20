<script setup>
import { FileText } from "lucide-vue-next";
import PermissionCard from "./PermissionCard.vue";
import { useLeaveRequests } from "../../../../composables/Leave/useLeaveRequests";
import { onMounted } from "vue";

const { requests, fetchLeaveRequestsPerUser } = useLeaveRequests();
onMounted(() => {
    fetchLeaveRequestsPerUser();
});

</script>

<template>
    <section class="m-3 p-4">
        <div class="flex items-center">
            <FileText class="w-6 h-6 md:h-6 md:w-10 lg:h-7 lg:w-10"/>
            <h1 class="ml-3 text-2xl md:text-2xl lg:text-3xl">Mis solicitudes</h1>
        </div>
        <div class="mt-2">
            <p class="text-sm md:text-md md:text-sm lg:text-lg text-gray-600">Historial de tus solicitudes de permiso o vacaciones.</p>
        </div>
    </section>
    <section>
        <PermissionCard
            v-for="request in requests"
            :type="request.type"
            :startDate="request.start_date"
            :endDate="request.end_date"
            :reason="request.reason"
            :status="request.status"
        />
    </section>
</template>
