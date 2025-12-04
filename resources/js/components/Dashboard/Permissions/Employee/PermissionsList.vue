<script setup>
    import { FileText } from "lucide-vue-next";
    import PermissionCard from "./PermissionCard.vue";
</script>

<template>
    <section class="m-3 p-4">
        <div class="flex items-center">
            <FileText class="w-7 h-7"/>
            <h1 class="ml-3 text-3xl">Mis solicitudes</h1>
        </div>
        <div class="mt-2">
            <p class="text-md text-gray-600">Historial de tus solicitudes de permiso o vacaciones.</p>
        </div>
    </section>
    <section>
        <PermissionCard
            :type="'Permiso'"
            :startDate="'2025-01-01'"
            :endDate="'2025-01-02'"
            :reason="'Asunto personal'"
            :status="'Aprobado'"
        />
        <PermissionCard
            :type="'Permiso'"
            :startDate="'2025-01-01'"
            :endDate="'2025-01-02'"
            :reason="'Asunto personal'"
            :status="'Pendiente'"
        />
        <PermissionCard
            :type="'Permiso'"
            :startDate="'2025-01-01'"
            :endDate="'2025-01-02'"
            :reason="'Asunto personal'"
            :status="'Rechazado'"
        />
    </section>
</template>
