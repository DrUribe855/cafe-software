<script setup>
import Card from '@/components/Dashboard/OptionsCard.vue';
import { dashboardCards } from '../../data/dashboard/dashboardCards';
import { RouterLink } from 'vue-router';
import { useUserStore } from '@/stores/userStore';
import { onMounted, ref } from 'vue';

const userStore = useUserStore();
const userRole = ref();
const isLoading = ref(true);
const visibleModules = ref([]);

onMounted(async () => {
    await userStore.fetchUser();
    userRole.value = userStore.user.role;
    if(userRole.value){
        visibleModules.value = dashboardCards.filter(card => card.roles.includes(userRole.value));
    }
    isLoading.value = false;
    console.log('User Role:', userRole.value); // Verifica que el rol se haya asignado correctamente

});

</script>

<template>
    <p v-if="isLoading">Cargando información...</p>
    <div v-else class="grid gap-6 mb-8 px-6 mx-auto grid-cols-[repeat(auto-fit,minmax(250px,1fr))] justify-start">
    <!-- Se hace el renderizado de cada uno de las tarjetas como link -->
    <RouterLink
        v-for="card in visibleModules"
        :to="card.route"
        :key="card.title"
        
    >
        <Card
            v-if="card.roles.includes(userRole)"
            :title="card.title"
            :description="card.description"
            :icon="card.icon"
        />
    </RouterLink>
</div>
</template>
