<script setup>

import { useUsers } from '../../../composables/Users/useUsers';
import { useEstablishmentStore } from '../../../stores/establishmentStore';
import { ref } from 'vue';

const props = defineProps({
    status: {
        required: true
    },
    user: {
        type: Object,
        required: false,
    }
});

const emits = defineEmits(['close','addUser']);
const establishmentStore = useEstablishmentStore();
const user = ref({
    id: props.user?.id ?? '',
    document: props.user?.document ?? '',
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    role:  props.user?.roles[0].name,
    status: props.user?.status ?? '',
    establishmentId: establishmentStore.getCode(),
});

const userComposable = useUsers();
const { errors } = userComposable;

const save = async () => {
    const newUser = await userComposable.saveUser(user.value);
    console.log('Valor de newUser: ', newUser);

    if(newUser){
        emits('addUser', newUser);
        emits('close');
    }

}


</script>



<template>
    <div
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <!-- Fondo -->
        <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px] transition-opacity duration-300"></div>

        <!-- Contenedor modal -->
        <div
            class="relative bg-white rounded-2xl shadow-md w-full max-w-lg p-6 transform transition-all duration-300 opacity-100 scale-100"
        >
            <!-- Botón cerrar -->
            <button
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                @click="emits('close')"

            >
                ✕
            </button>

            <!-- Título -->
            <h2 class="text-xl font-semibold mb-4">
                {{ user.id ? 'Modificar usuario' : 'Registrar usuario' }}
            </h2>

            <!-- Formulario -->
            <form @submit.prevent="" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">DNI</label>
                        <input
                            type="number"
                            v-model="user.document"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.document">¡{{ errors.document[0] }}!</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium" name="name">Nombre</label>
                        <input
                            type="text"
                            v-model="user.name"
                            name="name"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.name">¡{{ errors.name[0] }}!</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input
                            type="email"
                            v-model="user.email"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.email">¡{{ errors.email[0] }}!</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Rol</label>
                        <select
                            v-model="user.role"
                            name="role"
                            id="role"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                        >
                            <option value="employee" selected >Empleado</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.role">¡{{ errors.role[0] }}!</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Estado</label>
                        <select
                            v-model="user.status"
                            name="status"
                            id="status"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                        >
                            <option value="Activo" selected >Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.status">¡{{ errors.status[0] }}!</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Contraseña</label>
                        <input
                            type="password"
                            v-model="user.password"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
                        <p class="pt-2 text-red-500 text-center text-xs" v-if="errors.password">¡{{ errors.password[0] }}!</p>
                    </div>
                </div>

                <button
                    @click="save()"
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition"
                >
                    {{ user.id ? "Actualizar" : "Registrar" }}
                </button>
            </form>
        </div>
  </div>
</template>
