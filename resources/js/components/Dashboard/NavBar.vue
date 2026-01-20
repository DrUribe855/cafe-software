<script setup>
import { useUserStore } from '@/stores/userStore';
import { useAuth } from '@/composables/Auth/useAuth';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { ArrowRightOnRectangleIcon } from '@heroicons/vue/24/outline';
import { onMounted, ref } from 'vue';

const userStore = useUserStore()
const establishmentStore = useEstablishmentStore();
const authComposable = useAuth();
const stores = ref([]);

const changeStore = (event) => {
    establishmentStore.setCode(event.target.value);
}

const fetchStores = async () => {
    const { data } = await axios.get('/api/establishments');
    stores.value = data;
}

onMounted( async () => {
    await fetchStores();
    establishmentStore.setCode(stores.value[0].id);
})

</script>


<template>
    <nav class="flex items-center h-15 px-4 bg-white shadow-xs mb-5">
        <!-- Primer item a la izquierda -->
        <div class="flex-shrink-5 text-sm md:text-base leading-tight"></div>
        <!-- Bienvenida en PC -->
        <p class="hidden md:block font-semibold text-lg">¡Bienvenido {{userStore.user.name}}!</p>
        <!-- Bienvenida en móviles -->
        <div class="block md:hidden">
            <p class="font-bold text-lg pl-1">¡Bienvenido</p>
            <p class="font-bold text-base">{{ userStore.user.name }}!</p>
        </div>

    <!-- Espaciador para empujar lo demás a la derecha -->
    <div class="flex-15"></div>

    <!-- Items a la derecha -->
    <div class="flex items-center gap-4">
        <select
            @change="changeStore"
            v-if="userStore.user.role == 'admin'"
            class="border rounded-lg px-4 py-2 text-sm shadow-sm"
            name="store"
            id="store"
        >
            <option disabled selected>Tienda</option>
            <option
                v-for="store in stores" 
                :value="store.id"
                :key="store.id"
            >
                {{ store.name }}
            </option>
        </select>

    <!-- Botón cerrar sesión -->
    <button @click="authComposable.logout()" class="flex items-center gap-2 bg-red-500 text-white px-3 py-1.5 rounded-md hover:bg-red-600 transition">
      <!-- Icono para dispositivos móviles -->
      <ArrowRightOnRectangleIcon class="w-7 h-7"/>
      <span class="hidden md:inline text-sm font-medium">Cerrar sesión</span>
    </button>

    </div>
  </nav>
</template>
