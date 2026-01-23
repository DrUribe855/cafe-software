<script setup>
import { onMounted } from "vue";
import { storeToRefs } from "pinia";
import { useMenuProduct } from "/resources/js/composables/Pastrie/useMenu.js";
import { useMenuProducts } from "@/composables/Menu/Products/useMenuProducts.js";
import { useMenuStore } from "@/stores/Menu/menuStore.js";
import ProductsForm from "@/components/Dashboard/Menu/Products/ProductsForm.vue";
// import MenuUploaderImage from "@/components/Dashboard/Menu/MenuUploaderImage.vue";
import { Trash } from "lucide-vue-next";

const menuStore = useMenuStore();
const { categories, products } = storeToRefs(menuStore);
const { isEditing,
        category,
        isSelected,
        file,
        fetchProducts,
        addCustomTag,
        removeCustomTag,
        fileSelected,
        saveProduct,
        enableEditing,
        deleteProduct
    } = useMenuProducts();

const loadProducts = () => {
  if (selectedCategory.value) {
    fetchProducts(selectedCategory.value);
  } else {
    products.value = [];
  }
};

const remove = async (id) => {
  await deleteProduct(id);
  await loadProducts();
};

onMounted(async () => {
    await fetchProducts();
})

</script>

<template>
    <section class="flex flex-col gap-4 p-4 mt-3 bg-white shadow-sm rounded-lg mb-4">
        <h1 class="text-lg md:text-xl font-bold text-slate-800 border-l-4 border-sky-400 pl-3">
            Administración de productos
        </h1>

        <ProductsForm
            :categories   = "categories"
            :category     = "category"
            :isEditing    = "isEditing"
            :file         = "file"
            :isSelected   = "isSelected"
            :selectedFile = "fileSelected"
            @add-custom-tag    = "addCustomTag"
            @remove-custom-tag = "removeCustomTag"
            @save-product      = "saveProduct"
        />

        <div class="flex flex-col sm:flex-row gap-6 mt-4">

            <!-- Formulario de productos -->


            <!-- Lista de productos -->
            <div class="flex-1 bg-white p-5 rounded-lg shadow max-h-[520px] overflow-y-auto">
                <h2 class="font-bold text-lg mb-4 border-b pb-2">Productos creados</h2>

                <div v-if="!products.length" class="text-gray-500 italic">
                    No hay productos para esta categoría.
                </div>

                <div class="grid gap-4 sm:grid-cols-2 mt-4">
                    <div
                        v-for="p in products"
                        :key="p.id"
                        class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md hover:border-sky-400 transition"
                    >
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <p class="font-semibold text-slate-800">Categoría: {{ p.category?.name }}</p>
                                <p class="font-semibold text-slate-800">Nombre: {{ p.name }}</p>
                                <p class="font-semibold text-gray-800">Precio: {{ p.price }} €</p>
                                <p class="font-semibold text-gray-800">Etiqueta: {{ p.tag }}</p>
                                <div v-if="p.custom_tags">
                                    <p class="font-semibold text-gray-800">Alergenos: {{ p.custom_tags }}</p>
                                </div>
                            </div>

                            <button
                                @click="removeProduct(p.id)"
                                class="p-1 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition"
                            >
                                <Trash />
                            </button>
                        </div>

                        <button
                            @click="enableEditing(p)"
                            class="mt-3 text-sm text-sky-600 hover:underline"
                        >
                            Editar producto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
