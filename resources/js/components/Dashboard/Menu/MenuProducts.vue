<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const categories = ref([]);
const products = ref([]);
const selectedCategory = ref("");

const isEditing = ref(false);
const form = ref({
  id: null,
  category_id: "",
  name: "",
  description: "",
  price: "",
  image: null,
  tag: "",
  recommended: false,
});

// Cargar categorías
const fetchCategories = async () => {
  try {
    const res = await axios.get("/api/menu/categories");
    categories.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

// Cargar productos de la categoría
const fetchProducts = async () => {
  if (!selectedCategory.value) return;
  try {
    const res = await axios.get(`/api/menu/products/${selectedCategory.value}`);
    products.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

// Imagen seleccionada
const handleImage = (e) => {
  form.value.image = e.target.files[0];
};

// Limpiar formulario
const resetForm = () => {
  form.value = {
    id: null,
    category_id: selectedCategory.value,
    name: "",
    description: "",
    price: "",
    image: null,
    tag: "",
    recommended: false,
  };
  isEditing.value = false;
};

// Guardar / Actualizar
const saveProduct = async () => {
  if (!form.value.name || !form.value.price) {
    Swal.fire("Campos obligatorios", "Nombre y precio son requeridos", "warning");
    return;
  }

  const payload = new FormData();
  Object.entries(form.value).forEach(([key, value]) => {
    payload.append(key, value);
  });

  try {
    if (isEditing.value) {
      await axios.post(`/api/menu/products/update/${form.value.id}`, payload);
      Swal.fire("Actualizado", "Producto actualizado correctamente", "success");
    } else {
      await axios.post("/api/menu/products", payload);
      Swal.fire("Creado", "Producto creado correctamente", "success");
    }

    fetchProducts();
    resetForm();
  } catch (e) {
    console.error(e);
    Swal.fire("Error", "Hubo un problema guardando el producto", "error");
  }
};

// Editar
const editProduct = (p) => {
  isEditing.value = true;
  form.value = {
    id: p.id,
    category_id: p.category_id,
    name: p.name,
    description: p.description,
    price: p.price,
    image: null,
    tag: p.tag,
    recommended: p.recommended,
  };
};

// Eliminar
const deleteProduct = async (id) => {
  const confirm = await Swal.fire({
    title: "¿Eliminar producto?",
    text: "No podrás deshacer esta acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
  });

  if (!confirm.isConfirmed) return;

  try {
    await axios.delete(`/api/menu/products/${id}`);
    fetchProducts();
    Swal.fire("Eliminado", "Producto eliminado", "success");
  } catch (e) {
    console.error(e);
    Swal.fire("Error", "No se pudo eliminar", "error");
  }
};

onMounted(() => {
  fetchCategories();
});
</script>

<template>
  <section class="p-6">

    <h1 class="text-2xl font-bold mb-6">Administración de Productos</h1>

    <!-- Seleccionar categoría -->
    <div class="mb-6">
      <label class="font-semibold text-gray-700">Seleccionar categoría:</label>
      <select
        v-model="selectedCategory"
        @change="fetchProducts"
        class="p-2 border rounded w-64"
      >
        <option value="">Seleccione…</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- FORMULARIO -->
      <div class="bg-white p-5 rounded-lg shadow">
        <h2 class="font-bold text-lg mb-4">
          {{ isEditing ? "Editar producto" : "Nuevo producto" }}
        </h2>

        <div class="flex flex-col gap-3">

          <input
            type="text"
            v-model="form.name"
            placeholder="Nombre"
            class="p-2 border rounded"
          />

          <textarea
            v-model="form.description"
            placeholder="Descripción"
            class="p-2 border rounded"
          ></textarea>

          <input
            type="number"
            v-model="form.price"
            placeholder="Precio (€)"
            class="p-2 border rounded"
          />

          <select v-model="form.tag" class="p-2 border rounded">
            <option value="">Etiqueta…</option>
            <option value="dulce">Dulce</option>
            <option value="salado">Salado</option>
            <option value="frio">Frío</option>
            <option value="especialidad">Especialidad</option>
          </select>

          <label class="flex items-center gap-2">
            <input type="checkbox" v-model="form.recommended" />
            <span>Recomendado</span>
          </label>

          <input type="file" @change="handleImage" />

          <button
            @click="saveProduct"
            class="bg-blue-600 text-white px-4 py-2 rounded mt-3"
          >
            {{ isEditing ? "Actualizar" : "Crear" }}
          </button>

          <button
            v-if="isEditing"
            @click="resetForm"
            class="text-gray-600 text-sm mt-2 underline"
          >
            Cancelar edición
          </button>
        </div>
      </div>

      <!-- LISTA DE PRODUCTOS -->
      <div class="bg-white p-5 rounded-lg shadow">
        <h2 class="font-bold text-lg mb-4">Productos</h2>

        <div v-if="!products.length" class="text-gray-500">
          No hay productos para esta categoría.
        </div>

        <div v-for="p in products" :key="p.id" class="flex items-center justify-between border-b py-3">
          <div>
            <p class="font-semibold">{{ p.name }}</p>
            <p class="text-sm text-gray-600">{{ p.price }} €</p>
            <p class="text-xs text-gray-500">{{ p.tag }}</p>
          </div>

          <div class="flex gap-2">
            <button
              @click="editProduct(p)"
              class="px-2 py-1 bg-yellow-400 text-white rounded text-sm"
            >
              Editar
            </button>

            <button
              @click="deleteProduct(p.id)"
              class="px-2 py-1 bg-red-500 text-white rounded text-sm"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
