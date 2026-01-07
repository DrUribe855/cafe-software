<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUsers } from '../../../composables/Users/useUsers';
import { alert } from '../../../composables/Pastrie/alert';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import { UserPlus, SquarePen, Trash } from "lucide-vue-next";
import UserModal from './FormModal.vue';

/* Declaracion de variables */
const establishmentStore = useEstablishmentStore();
const userComposable = useUsers();
const { fetchUsers, users, pagination, deleteUser } = userComposable;
const selectedUser = ref(null);
const modalStatus = ref(false);
const searchUser = ref('');
const router = useRouter();

/* Función para apertura de modal y cargado de usuario enviado */
const openModal = ( user ) => {
    selectedUser.value = user;
    modalStatus.value = true;
}

/* Función para cierre de modal y limpieza de usuario seleccionado */
const closeModal = () =>{
    modalStatus.value = false;
    selectedUser.value = null;
}

/* Función para añadir usuario al arreglo de usuarios */
const addUser = async (newUser) => {
    if(newUser){
        const index = users.value.findIndex((user) => user.id == newUser.id);

        if(index !== -1){
            users.value[index] = newUser;
            console.log("Usuario modificado: ", users.value[index]);
            alert('Validado','Usuario modificado correctamente', 'success');
        }else{
            users.value.push(newUser);
            alert('Validado','Usuario creado correctamente', 'success');
        }
    }
}

/* Función para hacer eliminado lógico de usuario */

const removeUser = async (user) => {
    if(user){
        await deleteUser(user);
        users.value = users.value.filter(u => u.id !== user.id);
        alert('Validado','Usuario eliminado correctamente', 'success');
    }
}

/* Función para buscar usuarios en la barra de busqueda */
const filteredUsers = computed(() => {
    // retorna todos los usuarios si no hay datos en la busqueda
    if(!searchUser.value) return users.value;

    return users.value.filter(user => {
        return (
        user.document.toString().includes(searchUser.value) ||
        user.name.toLowerCase().includes(searchUser.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchUser.value.toLowerCase()) ||
        user.roles[0].name.toLowerCase().includes(searchUser.value.toLowerCase()) ||
        user.status.toLowerCase().includes(searchUser.value.toLowerCase())
        );
    });
});

/* Cargado de variables al cargar la página */
onMounted(()=>{
    fetchUsers(establishmentStore.code);
});

/* Watcher para actualizar tabla al modificar usuarios */

watch(userComposable.users, (newValue) => {
  users.value = newValue;
}, { immediate: true });

/* Watcher para actualizar tabla al cambiar de tienda */
watch( () => establishmentStore.code, (newStore) => {
    if(newStore){
        users.value = fetchUsers(newStore);
    }
})
</script>


<template>
       <!-- Boton volver -->
    <div>
        <button @click="router.back()" class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <!-- Icono para dispositivos móviles -->
            <span class="hidden md:inline text-sm font-medium">Volver</span>
        </button>
        <br>
    </div>
    <div class="w-full flex flex-col gap-3 md:flex-row md:justify-between md:items-center mb-3 mt-1 pl-3 pr-3">
    <!-- Título -->
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Lista de empleados</h3>
            <p class="text-slate-500 text-sm">Gestiona tu personal</p>
        </div>

    <!-- Barra de búsqueda -->
        <div class="w-full sm:w-80 md:w-95 lg:w-[25rem]">
            <div class="flex items-center">
                <!-- Input -->  
                <input v-model="searchUser"
                    class="w-full h-10 px-3 bg-white placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-l-md transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Buscar..."
                    name="search-user"
                />
                <!-- Botón -->
                <button
                    class="h-10 px-3 flex items-center justify-center bg-white border border-l-0 border-slate-200 rounded-r-md hover:bg-gray-100"
                    type="button"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A 7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Botón de crear usuario -->
        <div class="w-full md:w-auto flex">
            <button
                @click="modalStatus = !modalStatus"
                class="flex items-center gap-2 bg-sky-400 text-white px-4 py-2 rounded-lg shadow hover:bg-sky-300 transition"
            >
                <UserPlus class="w-5 h-5" />
                <span>Crear usuario</span>
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
        <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">DNI</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Nombre</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Email</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Rol</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Estado</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Modificar</th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-slate-50" v-for="user in filteredUsers" :key="user.id">
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="block font-semibold text-sm text-slate-800">{{ user.document }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500">{{ user.name }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500">{{ user.email }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500">{{ user.roles[0].name }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <p class="text-sm text-slate-500">{{ user.status }}</p>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <button @click="openModal(user)" class="pl-5"><SquarePen/></button>
                    </td>
                    <td class="p-4 border-b border-slate-200 py-5">
                        <button @click="removeUser(user)" class="pl-5"><Trash/></button>
                    </td>
                </tr>
            </tbody>
        </table>

         <!-- Paginador de tabla usuarios -->
             <div class="flex justify-between items-center mt-6 px-4">
             <button :disabled="pagination.current_page === 1" @click="fetchUsers(establishmentStore.code, pagination.current_page - 1)"
             class="flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm border border-gray-300 bg-white text-gray-700 hover:bg-sky-100 hover:border-sky-300 hover:text-sky-600
             disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white transition duration-200 ease-in-out"> 
             
             <span class="text-lg">◀</span>
             <span class="hidden sm:inline">Anterior</span>
             </button>

             <span class="text-sm text-gray-600">
                Página {{ pagination.current_page }} de {{ pagination.last_page }}
            </span>

            <button :disabled="pagination.current_page === pagination.last_page" @click="fetchUsers(establishmentStore.code, pagination.current_page + 1)"
            class="flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm border border-gray-300 bg-sky text-gray-700 hover:bg-sky-100 hover:border-sky-300 hover:text-sky-600
             disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-sky transition duration-200 ease-in-out"> 

             <span class="text-lg">▶</span>
             <span class="hidden sm:inline">Siguiente</span>

             </button>
            </div>
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
            v-if="modalStatus"
            :user="selectedUser"
            :status="modalStatus"
            @close="closeModal"
            @addUser="addUser"
        />
    </transition>
</template>
