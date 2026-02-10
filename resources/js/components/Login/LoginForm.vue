<template>
    <!-- Imagen de fondo -->
    <div
        class="h-screen bg-cover bg-center blur-[3px] relative"
        style="background-image: url('/images/background-login.webp');">
    </div>

    <!-- Contenedor login -->
    <div class="fixed inset-0 flex items-center justify-center z-10 my-2 p-3">
        <section class="bg-[#292e46] p-4 rounded-lg shadow-lg w-[22em] h-full">
            <div class="flex justify-center mt-4">
                <div class="bg-amber-300 p-4 rounded-full shadow-lg shadow-amber-400/50">
                    <Coffee class="text-gray-950 m-1 w-[1.8em] h-[1.8em]"/>
                </div>
            </div>
            <div class="mt-[2em]">
                <div class="flex justify-center">
                    <h1 class="text-amber-50 text-3xl font-bold">Bienvenido</h1>
                </div>
                <div class="flex justify-center mt-2">
                    <p class="text-gray-400 text-sm">Ingresa tus credenciales para continuar</p>
                </div>
            </div>
            <form @submit.prevent="login(document, password)">
                <div class="mt-4">
                    <p class="pt-2 text-red-500 text-center" v-if="errors.userNotFound">¡{{ errors.userNotFound }}!</p>
                    <p class="pt-2 text-red-500 text-center" v-if="errors.incorrectUser">¡{{ errors.incorrectUser }}!</p>
                    <p class="pt-2 text-red-500 text-center" v-if="errors.inactiveUser">¡{{ errors.inactiveUser }}!</p>
                    <div class="block m-3">
                        <label class="block text-gray-400 font-bold pb-2 text-[0.9em]" for="document">DNI</label>
                        <div class="relative group">
                            <div class="absolute left-2 top-1/2 -translate-y-1/2 text-coffee-400 group-focus-within:text-gold transition-colors duration-300">
                                <User class="w-5 h-5" />
                            </div>
                            <input
                                v-model="document"
                                class="bg-white/90 border-0 ring-1 ring-white/20 text-foreground w-full rounded-xl
                                        pl-12 h-12 text-coffee-800  focus:bg-white transition-all duration-300"
                                type="text"
                                placeholder="Ingrese su DNI"
                            >
                        </div>
                        <p class="pt-2 text-red-500" v-if="errors.document">¡{{ errors.document[0] }}!</p>
                    </div>
                    <div class="block m-3">
                        <label class="block text-gray-400 font-bold pb-2 text-[0.9em]" for="password">Contraseña</label>
                        <div class="relative group">
                            <div class="absolute left-2 top-1/2 -translate-y-1/2 text-coffee-400 group-focus-within:text-gold transition-colors duration-300">
                                <Lock class="w-5 h-5" />
                            </div>
                            <input
                                v-model="password"
                                class="bg-white/90 border-0 ring-1 ring-white/20 text-foreground w-full rounded-xl
                                        pl-12 h-12 text-coffee-800  focus:bg-white transition-all duration-300"
                                type="password"
                                placeholder="Ingrese su contraseña"
                            >
                        </div>
                        <p class="pt-2 text-red-500" v-if="errors.password">¡{{ errors.password[0] }}!</p>
                    </div>
                    <div class="m-3 mt-[1.8em]">
                        <button
                            class="w-full h-12 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-700
                                to-gray-600 hover:opacity-90 transition-opacity duration-300 hover:shadow-lg
                                hover:shadow-[hsl(225_52%_41%_/_0.3)] hover:scale-[1.02] active:scale-[0.98]
                                  ease-out text-base overflow-hidden relative"
                        >
                            Ingresar
                        </button>

                    </div>
                    <p className="text-center text-white/30 text-xs mt-8">
                        © 2026 Liaopastel. <br/>
                        Todos los derechos reservados.
                    </p>
                </div>
            </form>
        </section>
    </div>

</template>

<script setup>
import { Coffee, User, Lock } from 'lucide-vue-next';
import { useAuth } from '@/composables/Auth/useAuth';
import { ref } from 'vue';

const { login, errors } = useAuth();
const document = ref(null);
const password = ref('');

</script>
