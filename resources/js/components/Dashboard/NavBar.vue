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
    <nav class="flex items-center justify-between h-15 px-4  py-3 bg-white shadow-xs mb-5 w-full">
        
        <!-- Izquierda -->
        <div class="flex flex-col">
            <p class="font-semibold text-sm lg:text-lg">¡Bienvenido!</p>
            <p class="font-semibold text-sm lg:text-lg truncate">{{ userStore.user.name.split(' ')[0] }}
</p>
        </div>

        

        <!-- Derecha -->

        <div class="flex items-center gap-3 flex-shrink-0">

            <select 
                @change="changeStore"
                v-if="userStore.user.role === 'admin'"
                class="border rounded-lg px-3 py-1.5 text-sm shadow-sm max-w-[120px] truncate"
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
            <button 
                @click="authComposable.logout()" 
                class="flex items-center gap-2 bg-red-500 text-white px-3 py-1.5 rounded-md hover:bg-red-600 transition flex-shrink-0"
            >
                <ArrowRightOnRectangleIcon class="w-7 h-7" />
                <span class="hidden md:inline text-sm font-medium">Cerrar sesión</span>
            </button>
        </div>
  </nav>
</template>
