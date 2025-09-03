<script setup>
import Card from '@/components/Dashboard/OptionsCard.vue';
import { dashboardCards } from '../../data/dashboard/dashboardCards';
import { RouterLink } from 'vue-router';
</script>

<template>
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4 px-6 mx-auto">
        <!-- Se hace el renderizado de cada uno de las tarjetas como link -->
        <!-- Se itera los items del arreglo de dashboardCards mediante el v-for -->
        <!-- Se renderiza por defecto el componente DashboardMenu -->
        <RouterLink
            v-for="card in dashboardCards"
            :to="card.route"
            :key="card.title"
        >
            <Card
                :title="card.title"
                :description="card.description"
                :icon="card.icon"
            />
        </RouterLink>
    </div>
</template>
