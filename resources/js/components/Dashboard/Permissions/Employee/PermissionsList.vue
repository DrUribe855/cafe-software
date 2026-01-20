<script setup>
import { watch, computed } from "vue";
import PermissionCard from "./PermissionCard.vue";
import Loader from "../../../Loader.vue";
import { useLoader } from '../../../../composables/useLoader';
import { FileText, ChevronLeft, ChevronRight } from "lucide-vue-next";
import { useUserLeaveRequests } from "../../../../composables/Leave/Employee/useUserLeaveRequests";
import { useDateFilter } from "../../../../composables/DateFilters/useDateFilter";


const { requests, fetchLeaveRequestsPerUser } = useUserLeaveRequests();
const { currentDate, previousMonth, nextMonth } = useDateFilter();
const { isLoading } = useLoader();

const month = computed(() =>
  currentDate.value.toLocaleString('es-ES', { month: 'long' })
)

const year = computed(() =>
  currentDate.value.getFullYear()
)

watch(currentDate, (date) => {
        fetchLeaveRequestsPerUser(date);
    },
    { immediate: true }
);

</script>

<template>
    <template v-if="isLoading">
        <Loader/>
    </template>
    <section class="m-3 p-4">
    <!-- Header -->
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <!-- Título -->
            <div class="flex items-center gap-2">
                <FileText class="w-6 h-6 md:w-8 md:h-8" />
                <h1 class="text-base md:text-lg lg:text-2xl font-semibold">
                    Mis solicitudes
                </h1>
            </div>

            <!-- Filtro de mes -->
            <div class="flex items-center justify-between md:justify-end gap-3">

                <ChevronLeft
                    class="w-6 h-6 cursor-pointer text-gray-600 hover:text-sky-500"
                    @click="previousMonth(2)"
                />

                <span class="text-sm md:text-base text-gray-600 capitalize">
                    {{ month }} {{ year }}
                </span>

                <ChevronRight
                    class="w-6 h-6 cursor-pointer text-gray-600 hover:text-sky-500"
                    @click="nextMonth(2)"
                />
            </div>

        </div>

        <!-- Subtítulo -->
        <p class="mt-2 text-sm md:text-base text-gray-600">
            Historial de tus solicitudes de permiso o vacaciones.
        </p>
    </section>
    <section>
        <template v-if="requests.length > 0">
            <PermissionCard
                v-for="request in requests"
                v-bind="request"
            />
        </template>

        <p v-else class="text-center text-gray-500 py-6">
            No hay solicitudes para mostrar
        </p>
    </section>
</template>
