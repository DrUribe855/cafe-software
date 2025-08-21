<script setup lang="ts">
import Card from '@/components/Dashboard/OptionsCard.vue';
import { dashboardCards } from '../data/dashboard/dashboardCards';
</script>

<template>
    <div class="container px-6 mx-auto-grid">
        <Card
            v-for="card in dashboardCards"
            :title="card.title"
            :description="card.description"
            :icon="card.icon"
        />
    </div>
</template>
