<script setup>
import { useRouter } from "vue-router";
import CategoryForm from "./CategoriesForm.vue";
import { useMenuCategories } from "/resources/js/composables/Menu/Categories/useMenuCategories.js";
import { useMenuStore } from "@/stores/Menu/menuStore.js";
import CategoriesList from "./CategoriesList.vue";
import { onMounted } from "vue";

const router = useRouter();

const { category, saveCategory, deleteCategory, fetchCategories } = useMenuCategories();
const menuStore = useMenuStore();

onMounted( async () => {
    await fetchCategories();
});

const goBack = () => router.back();
</script>

<template>

    <!-- Botón de regreso -->
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

    <!-- Seccion para manejo de categorias del menu -->
    <section class="bg-white rounded-xl shadow p-6 space-y-6">

        <h1 class="text-xl font-bold text-slate-800 border-l-4 border-sky-500 pl-3">
            Categorías del menú
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Formulario para crear nueva categoría -->
            <CategoryForm
                :category = "category"
                @save-category="saveCategory"
            />

            <!-- Lista de categorías creadas -->
            <CategoriesList
                :categories="menuStore.categories"
                @delete-category="deleteCategory"
            />
        </div>
    </section>
</template>
