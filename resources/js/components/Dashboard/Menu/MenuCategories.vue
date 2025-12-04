<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const categories = ref([]);
const newCategory = ref({ name: "", description: "" });

const fetchCategories = async () => {
  const res = await axios.get("/api/menu/categories");
  categories.value = res.data;
};

const saveCategory = async () => {
  await axios.post("/api/menu/categories", newCategory.value);
  newCategory.value = { name: "", description: "" };
  fetchCategories();
};

const deleteCategory = async (id) => {
  await axios.delete(`/api/menu/categories/${id}`);
  fetchCategories();
};

onMounted(fetchCategories);
</script>

<template>
  <section class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Categorías del menú</h1>

    <!-- Crear nueva -->
    <div class="bg-white p-4 shadow rounded-lg mb-6">
      <input v-model="newCategory.name"
             placeholder="Nombre"
             class="border p-2 w-full mb-2 rounded" />

      <textarea v-model="newCategory.description"
                placeholder="Descripción"
                class="border p-2 w-full rounded"></textarea>

      <button @click="saveCategory"
              class="bg-sky-500 text-white px-4 py-2 mt-3 rounded">
        Crear categoría
      </button>
    </div>

    <!-- Lista -->
    <div v-for="c in categories" :key="c.id"
         class="bg-white p-4 rounded shadow mb-3 flex justify-between items-center">

      <div>
        <p class="font-bold">{{ c.name }}</p>
        <p class="text-gray-600">{{ c.description }}</p>
      </div>

      <button @click="deleteCategory(c.id)"
              class="text-red-600 font-semibold">
        Eliminar
      </button>
    </div>
  </section>
</template>
