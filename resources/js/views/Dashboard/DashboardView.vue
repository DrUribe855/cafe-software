<script setup>

import { onMounted } from 'vue';
import NavBar from '@/components/Dashboard/NavBar.vue';
import { useUserStore } from '@/stores/userStore';

const userStore = useUserStore();
const { getUser, setUser } = userStore;

onMounted(() => {
    getUser();
})



</script>

<template>
    <div class="bg-[#edf2fa] min-h-screen">
        <!-- Componente de navbar -->
        <NavBar/>
        <!-- Se renderizan las rutas hijas vinculadas al componente dashboardView -->
        <router-view/>
    </div>
</template>

<style scoped>

</style>
