<script setup lang="ts">
import Card from '@/components/Dashboard/OptionsCard.vue';
import NavBar from '@/components/Dashboard/NavBar.vue';
import { dashboardCards } from '../data/dashboard/dashboardCards';
import { useUserStore } from '@/stores/userStore';
import { storeToRefs } from 'pinia';

const userStore = useUserStore();
const { fetchUser, setUser } = userStore;

fetchUser();



</script>

<template>
    <div class="bg-[#edf2fa] h-screen">
        <NavBar/>
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4 px-6 mx-auto">
            <a v-for="card in dashboardCards" href="">
                <Card
                :title="card.title"
                :description="card.description"
                :icon="card.icon"
                />
            </a>
        </div>
    </div>

</template>

<style scoped>

</style>
