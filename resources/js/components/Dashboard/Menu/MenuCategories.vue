<script setup>
import { ref } from "vue";
import { useMenuCategories } from "/resources/js/composables/Pastrie/useMenu.js";
import { useRouter } from "vue-router";
import { Trash } from "lucide-vue-next";

const router = useRouter();
const newCategory = ref({ name: "", description: "" });

const { categories, saveCategory, deleteCategory } = useMenuCategories();

const goBack = () => router.back();
</script>

<template>
  <div class="w-full flex md:justify-start mb-4">
  <button
    @click="goBack"
    class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition"
  >
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
    <span class="hidden md:inline text-sm font-medium">Volver</span>
  </button>
  </div>

  <section class="bg-white rounded-xl shadow p-6 space-y-6">
    <h1 class="text-xl font-bold text-slate-800 border-l-4 border-sky-500 pl-3">
      Categorías del menú
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div>
        <h2 class="text-base font-semibold text-slate-700 mb-3">
          Añadir nueva categoría
        </h2>

        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <label class="text-sm font-semibold text-slate-700">
              Nombre de la categoría
            </label>
            <input
              v-model="newCategory.name"
              class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
            />
          </div>

          <div class="flex flex-col gap-1">
            <label class="text-sm font-semibold text-slate-700">
              Descripción
            </label>
            <textarea
              v-model="newCategory.description"
              class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none resize-none"
            ></textarea>
          </div>
        </div>

        <div class="flex justify-center">
          <button
            @click="saveCategory(newCategory)"
            class="mt-4 bg-sky-500 hover:bg-sky-400 text-white font-medium px-4 py-2 rounded transition"
          >
            Crear categoría
          </button>
        </div>
      </div>

      <div class="bg-white p-5 rounded-lg shadow">
        <h2 class="font-bold text-lg mb-4 border-b pb-2">
          Categorías creadas
        </h2>

        <div v-if="!categories.length" class="text-gray-500 italic">
          No hay categorías creadas.
        </div>

        <div class="grid gap-4 max-h-[520px] overflow-y-auto pr-1">
          <div
            v-for="c in categories"
            :key="c.id"
            class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm
                   hover:shadow-md hover:border-sky-400 transition"
          >
            <div class="flex justify-between items-start gap-4">
              <div class="flex-1">
                <p class="font-semibold text-slate-800">
                  {{ c.name }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                  {{ c.description || "Sin descripción" }}
                </p>
              </div>

              <button
                @click="deleteCategory(c.id)"
                class="p-1 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition"
              >
                <Trash class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
