<script setup>

import { ref, watch, onMounted } from 'vue';
import { useUsers } from '../../../composables/Users/useUsers';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import UserModal from './FormModal.vue';

const establishmentStore = useEstablishmentStore();
const userComposable = useUsers();
const { fetchUsers } = userComposable;
const users = ref([]);
const modalStatus = ref(false);

const closeModal = () =>{
    modalStatus.value = false;
}

const addUser = async (newUser) => {
    users.value.push(newUser);
}

onMounted(()=>{
    fetchUsers(establishmentStore.code);
})

watch(userComposable.users, (newValue) => {
  users.value = newValue;
}, { immediate: true });


</script>



<template>
    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Lista de empleados</h3>
            <p class="text-slate-500 text-sm">Gestiona tu personal</p>
        </div>
        <div class="ml-3">
            <div class="w-full max-w-sm min-w-[200px] relative">
                <div class="relative">
                    <input
                        class="bg-white w-full pr-11 h-10 pl-3 py-2 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                        placeholder="Buscar..."
                    />
                        <button
                            class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                            type="button"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- <div>
            <button @click="modalStatus = !modalStatus" class="bg-blue-500 p-1.5 rounded-sm text-white">Crear usuario</button>
        </div> -->
    </div>

    <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
        <table class="w-full text-left table-auto min-w-max">
            <thead>
                <tr>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p class="block text-sm font-normal leading-none text-slate-500">
                            DNI
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p class="block text-sm font-normal leading-none text-slate-500">
                            Nombre
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p class="block text-sm font-normal leading-none text-slate-500">
                            Email
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p class="block text-sm font-normal leading-none text-slate-500">
                            Rol
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p class="block text-sm font-normal leading-none text-slate-500">
                            Estado
                        </p>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-slate-50" v-for="user in users" :key="user?.id">
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="block font-semibold text-sm text-slate-800">{{ user.document }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500"> {{ user.name }} </p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500"> {{ user.email }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500">{{ user.roles[0].name }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500"> {{ user.status }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <UserModal
            :status="modalStatus"
            @close="closeModal"
            @addUser="addUser"
        />
    </transition>

</template>
