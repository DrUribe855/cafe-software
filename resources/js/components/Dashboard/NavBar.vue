<script setup>
import { useUserStore } from '@/stores/userStore';
import { useAuth } from '@/composables/Auth/useAuth';

const userStore = useUserStore();
const authComposable = useAuth();

</script>


<template>
  <nav class="flex items-center h-15 px-4 bg-white shadow-xs mb-5">
    <!-- Primer item a la izquierda -->
    <div class="flex-shrink-0">
      <p class="font-semibold text-1xl">¡Bienvenido {{ userStore.user.name }}!</p>
    </div>

    <!-- Espaciador para empujar lo demás a la derecha -->
    <div class="flex-1"></div>

    <!-- Items a la derecha -->
    <div class="flex items-center gap-4">
      <select v-if="userStore.user.role == 'admin'" class="border rounded px-2 py-1" name="" id="">
        <option value="">Tienda 1</option>
        <option value="">Tienda 2</option>
      </select>
      <button @click="authComposable.logout()" class="bg-red-500 text-white px-3 py-1 rounded">Cerrar sesión</button>
    </div>
  </nav>
</template>
