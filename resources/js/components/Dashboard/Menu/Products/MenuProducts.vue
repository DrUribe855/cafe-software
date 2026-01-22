<script setup>
import { ref, onMounted } from "vue";
import { useMenuProducts } from "/resources/js/composables/Pastrie/useMenu.js";
import { useMenuCategories } from "@/composables/Menu/Categories/useMenuCategories.js";
import { useMenuStore } from "@/stores/Menu/menuStore.js";
import MenuUploaderImage from "@/components/Dashboard/Menu/MenuUploaderImage.vue";
import { Trash } from "lucide-vue-next";

const menuStore = useMenuStore();
const { products, fetchProducts, saveProduct, deleteProduct } = useMenuProducts();

const selectedCategory = ref("");
const isEditing = ref(false);

const form = ref({
  id: null,
  category_id: "",
  name: "",
  description: "",
  price: "",
  image_url: null,
  tag: "",
  custom_tags: [],
  recommended: false,
});

const newCustomTag = ref("");

const onImageSelected = (file) => {
  form.value.image_url = file;
};

const resetForm = () => {
  form.value = {
    id: null,
    category_id: selectedCategory.value,
    name: "",
    description: "",
    price: "",
    image_url: null,
    tag: "",
    custom_tags: [],
    recommended: false,
  };
  newCustomTag.value = "";
  isEditing.value = false;
};

const loadProducts = () => {
  if (selectedCategory.value) {
    fetchProducts(selectedCategory.value);
  } else {
    products.value = [];
  }
};

const save = async () => {
  form.value.category_id = selectedCategory.value;

  const payload = {
    ...form.value,
    custom_tags: Array.isArray(form.value.custom_tags) ? form.value.custom_tags.join(",") : form.value.custom_tags ?? "",
  };

  await saveProduct(payload, isEditing.value);
  await loadProducts();
  resetForm();
};

const edit = (p) => {
  isEditing.value = true;
  form.value = {
    id: p.id,
    category_id: p.category_id,
    name: p.name,
    description: p.description,
    price: p.price,
    image_url: p.image_url,
    tag: p.tag,
    custom_tags: p.custom_tags ? p.custom_tags.split(",").map(t => t.trim()) : [],
    recommended: p.recommended,
  };
};

const remove = async (id) => {
  await deleteProduct(id);
  await loadProducts();
};

const addCustomTag = () => {
  if (!newCustomTag.value.trim()) return;
  form.value.custom_tags.push(newCustomTag.value.trim());
  newCustomTag.value = "";
};

const removeCustomTag = (index) => {
  form.value.custom_tags.splice(index, 1);
};

</script>

<template>
    <section class="flex flex-col gap-4 p-4 mt-3 bg-white shadow-sm rounded-lg mb-4">
        <h1 class="text-lg md:text-xl font-bold text-slate-800 border-l-4 border-sky-400 pl-3">
            Administración de productos
        </h1>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-3 md:mt-0">
            <label class="text-base font-semibold text-slate-700">Seleccionar categoría:</label>
            <select
                v-model="selectedCategory"
                @change="loadProducts"
                class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
            >
                <option disabled value="">Seleccionar categoría</option>
                <option v-for="cat in menuStore.categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
        </div>

        <div class="flex flex-col sm:flex-row gap-6 mt-4">
            <div class="flex-1">
                <h2 class="text-base font-semibold text-slate-700 mb-3">
                {{ isEditing ? "Editar producto" : "Añadir nuevo producto" }}
                </h2>

                <div class="flex flex-col gap-3">
                    <input
                        type="text"
                        v-model="form.name"
                        placeholder="Nombre"
                        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                    />

                    <textarea
                        v-model="form.description"
                        placeholder="Descripción"
                        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                    />

                    <input
                        type="number"
                        v-model="form.price"
                        placeholder="Precio (€)"
                        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                    />

                    <select
                        v-model="form.tag"
                        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                        <option disabled value="">Seleccionar etiqueta</option>
                        <option value="dulce">Dulce</option>
                        <option value="salado">Salado</option>
                        <option value="frio">Frío</option>
                        <option value="especialidad">Especialidad</option>
                    </select>

                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-1 block">
                            Etiquetas personalizadas
                        </label>

                        <div class="flex gap-2">
                            <input
                                type="text"
                                v-model="newCustomTag"
                                @keyup.enter="addCustomTag"
                                placeholder="Ej: Contiene maní"
                                class="flex-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                            />
                            <button
                                type="button"
                                @click="addCustomTag"
                                class="px-3 rounded-lg bg-sky-500 text-white hover:bg-sky-400 transition"
                            >
                                Añadir
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2">
                            <span
                                v-for="(tag, index) in form.custom_tags"
                                :key="index"
                                class="flex items-center gap-2 bg-sky-100 text-sky-700 text-xs px-3 py-1 rounded-full"
                            >
                                {{ tag }}
                                <button
                                    type="button"
                                    @click="removeCustomTag(index)"
                                    class="text-sky-500 hover:text-sky-700"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <span class="text-sm font-medium text-slate-700">
                            Producto recomendado
                        </span>
                        <div class="relative">
                            <input type="checkbox" v-model="form.recommended" class="sr-only" />
                            <div
                                class="w-10 h-5 bg-gray-300 rounded-full transition"
                                :class="form.recommended ? 'bg-sky-500' : 'bg-gray-300'"
                            ></div>
                            <div
                                class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition"
                                :class="form.recommended ? 'translate-x-5' : ''"
                            ></div>
                        </div>
                    </label>

                <MenuUploaderImage @selected="onImageSelected" />

                <button
                    @click="save"
                    class="self-center bg-sky-500 hover:bg-sky-400 text-white font-medium px-3 py-1 rounded transition"
                >
                    {{ isEditing ? "Actualizar" : "Añadir producto" }}
                </button>

                <button
                    v-if="isEditing"
                    @click="resetForm"
                    class="self-center bg-red-500 hover:bg-red-400 text-white font-medium px-3 py-1 rounded transition"
                >
                    Cancelar edición
                </button>
                </div>
            </div>

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
                @click="remove(p.id)"
                class="p-1 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition"
              >
                <Trash />
              </button>
            </div>

            <button
              @click="edit(p)"
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
