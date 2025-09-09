<template>

    <!-- Imagen de fondo -->
    <div
        class="h-screen bg-cover bg-center blur-[3px] relative"
        style="background-image: url('/images/login-background-image.webp');">
    </div>

    <!-- Contenedor login -->
    <div class="fixed inset-0 flex items-center justify-center z-10">
        <div class="bg-[#1a1a1a] p-4 rounded-lg shadow-lg w-[22em] ">
            <form @submit.prevent="login(document, password)">
                <div class="">
                    <p class="pt-2 text-red-500 text-center" v-if="errors.userNotFound">¡{{ errors.userNotFound }}!</p>
                    <p class="pt-2 text-red-500 text-center" v-if="errors.incorrectUser">¡{{ errors.incorrectUser }}!</p>
                    <p class="pt-2 text-red-500 text-center" v-if="errors.inactiveUser">¡{{ errors.inactiveUser }}!</p>
                    <div class="block m-3">
                        <label class="block text-white pb-1" for="document">DNI</label>
                        <input v-model="document" maxlength="20" class="block text-white border-1 border-gray-400 w-full rounded-sm p-1.5" type="number" placeholder="Ingrese su DNI">
                        <p class="pt-2 text-red-500" v-if="errors.document">¡{{ errors.document[0] }}!</p>
                    </div>
                    <div class="block m-3">
                        <label class="block text-white pb-1" for="password">Contraseña</label>
                        <input v-model="password" class="block text-white border-1 border-gray-400 w-full rounded-sm p-1.5" type="password" placeholder="Ingrese su contraseña">
                        <p class="pt-2 text-red-500" v-if="errors.password">¡{{ errors.password[0] }}!</p>
                    </div>
                    <p></p>
                    <div class="m-3">
                        <button class="text-center font-bold w-full bg-white rounded-md p-1.5 mt-3 transition delay-10 duration-400 ease-in-out hover:-translate-y-1 hover:scale-100 hover:bg-gray-200">Ingresar</button>
                    </div>
                    <div class="m-2">
                        <p class="text-white text-center pt-1"><a href="#">¿Has olvidado tú contraseña?</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>

<script setup>
import { useAuth } from '@/composables/Auth/useAuth';
import { ref } from 'vue';

const { login, errors } = useAuth();
const document = ref(null);
const password = ref('');

</script>

