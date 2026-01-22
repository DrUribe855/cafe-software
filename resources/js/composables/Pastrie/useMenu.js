import { ref, onMounted } from "vue";
import axios from "axios";
import { alert } from "./alert";
import Swal from "sweetalert2";

export function useMenuCategorie() {
  const categories = ref([]);
  const loading = ref(false);

  const fetchCategories = async () => {
    loading.value = true;
    try {
      const res = await axios.get("/api/menu/categories");
      categories.value = res.data;
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudieron cargar las categorías.",
      });
    } finally {
      loading.value = false;
    }
  };

  const saveCategory = async (category) => {
    if (!category.name?.trim()) {
      alert("Nombre requerido", "Debes ingresar un nombre para la categoría.", "error");
      return;
    }
    if (!category.description?.trim()) {
      alert("Descripción requerida", "Debes ingresar una descripción para la categoría.", "error");
      return;
    }
    try {
      await axios.post("/api/menu/categories", category);
      alert("Categoría creada", "La categoría se guardó correctamente.", "success");
      await fetchCategories();
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo crear la categoría.",
      });
    }
  };

  const deleteCategory = async (id) => {
    try {
      await axios.delete(`/api/menu/categories/${id}`);
      alert("Categoría eliminada", "La categoría fue eliminada correctamente.", "success");
      await fetchCategories();
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo eliminar la categoría.",
      });
    }
  };

  onMounted(fetchCategories);

  return {
    categories,
    loading,
    fetchCategories,
    saveCategory,
    deleteCategory,
  };
}

export function useMenuProducts() {
  const products = ref([]);
  const loading = ref(false);

  const fetchProducts = async (categoryId) => {
    if (!categoryId) {
      products.value = [];
      return;
    }
    try {
      const res = await axios.get("/api/menu/products", {
        params: { category_id: categoryId },
      });
      products.value = res.data;
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudieron cargar los productos.",
      });
    }
  };

  const saveProduct = async (data, isEditing = false) => {
    if (!data.name?.trim()) {
      alert("Nombre requerido", "Debes ingresar un nombre para el producto.", "error");
      return;
    }
    if (!data.price || data.price <= 0) {
      alert("Precio requerido", "Debes ingresar un precio válido.", "error");
      return;
    }
    if (!isEditing && !(data.image_url instanceof File)) {
      alert("Imagen requerida", "Debes subir una imagen antes de guardar el producto.", "error");
      return;
    }

    const formData = new FormData();
    formData.append("category_id", data.category_id);
    formData.append("name", data.name);
    formData.append("description", data.description ?? "");
    formData.append("price", data.price);
    formData.append("tag", data.tag ?? "");
    formData.append("custom_tags", Array.isArray(data.custom_tags) ? data.custom_tags.join(",") : data.custom_tags ?? "");
    formData.append("recommended", data.recommended ? 1 : 0);

    if (data.image_url instanceof File) {
      formData.append("image_url", data.image_url);
    }

    try {
      if (isEditing) {
        formData.append("_method", "PUT");
        await axios.post(`/api/menu/products/${data.id}`, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        alert("Producto actualizado", "El producto se editó correctamente.", "success");
      } else {
        await axios.post("/api/menu/products", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        alert("Producto creado", "El producto se registró correctamente.", "success");
      }
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo guardar el producto.",
      });
    }
  };

  const deleteProduct = async (id) => {
    try {
      await axios.delete(`/api/menu/products/${id}`);
      alert("Producto eliminado", "El producto fue eliminado correctamente.", "success");
    } catch {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo eliminar el producto.",
      });
    }
  };

  return {
    products,
    loading,
    fetchProducts,
    saveProduct,
    deleteProduct,
  };
}
