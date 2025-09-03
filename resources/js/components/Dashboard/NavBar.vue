<script setup>
import { useUserStore } from '@/stores/userStore';
import { useAuth } from '@/composables/Auth/useAuth';
import { ArrowRightOnRectangleIcon } from '@heroicons/vue/24/outline';

const userStore = useUserStore();
const authComposable = useAuth();

</script>


<template>
  <nav class="flex items-center h-15 px-4 bg-white shadow-xs mb-5">
    <!-- Primer item a la izquierda -->
    <div class="flex-shrink-5 text-sm md:text-base leading-tight">
    </div>
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
      <select v-if="userStore.user.role == 'admin'" class="border rounded-lg px-4 py-2 text-sm shadow-sm" name="" id="">
        <option value="">Tienda 1</option>
        <option value="">Tienda 2</option>
      </select>

    <!-- Botón cerrar sesión -->
    <button @click="authComposable.logout()" class="flex items-center gap-2 bg-red-500 text-white px-3 py-1.5 rounded-md hover:bg-red-600 transition">
      <!-- Icono para dispositivos móviles -->
      <ArrowRightOnRectangleIcon class="w-7 h-7" />
      <span class="hidden md:inline text-sm font-medium">Cerrar sesión</span>
    </button>

    </div>
  </nav>
</template>
