<script setup>

import { useUsers } from '../../../composables/Users/useUsers';
import { useEstablishmentStore } from '../../../stores/establishmentStore';
import { ref } from 'vue';

const props = defineProps({
    status: {
        required: true
    },
})

const emits = defineEmits(['close','addUser']);
const establishmentStore = useEstablishmentStore();

const user = ref({
    id: '',
    document: '',
    name: '',
    email: '',
    password: '',
    role: null,
    status: '',
    establishmentId: establishmentStore.getCode(),
});

const userComposable = useUsers();

const save = async () => {
    console.log('ejecuto');
    const newUser = await userComposable.createUser(user.value);
    console.log('usuario retornado: ', newUser);
    emits('addUser',newUser);
    emits('close');
}


console.log('valor de modalstatus: ', props.status);
</script>



<template>
    <div
        v-if="props.status"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <!-- Fondo -->
        <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px] transition-opacity duration-300"></div>

        <!-- Contenedor modal -->
        <div
            class="relative bg-white rounded-2xl shadow-md w-full max-w-lg p-6 transform transition-all duration-300 scale-95 opacity-0"
            :class="props.status ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
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
                Titulo
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input
                            type="text"
                            v-model="user.name"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Rol</label>
                        <select
                            v-model="user.role"
                            name="role"
                            id="role"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                        >
                            <option value="admin" selected >Empleado</option>
                            <option value="employee">Administrador</option>
                        </select>
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Contraseña</label>
                        <input
                            type="password"
                            v-model="user.password"
                            class="mt-2 block w-full p-[5px] border-1 rounded-md border-gray-300 focus:outline-blue-500"
                            required
                        />
                    </div>
                </div>

                <button
                    @click="save()"
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition"
                >
                    {{ true ? "Actualizar" : "Registrar" }}
                </button>
            </form>
        </div>
  </div>
</template>
