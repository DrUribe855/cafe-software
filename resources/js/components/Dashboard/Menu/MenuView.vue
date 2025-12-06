<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const categories = ref([]);
const recommended = ref(null);

const fetchMenu = async () => {
  try {
    const res = await axios.get("/api/menu/categories");
    categories.value = res.data;

    recommended.value =
      res.data.flatMap(c => c.products).find(p => p.recommended) || null;

  } catch (e) {
    console.error("Error cargando menú:", e);
  }
};

onMounted(fetchMenu);
</script>

<template>
  <section class="p-6 md:p-12 bg-[#F8F2ED] min-h-screen">

    <!-- Título principal -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-[#6B3F27] mb-4">
      Carta de Liao
    </h1>
    <p class="text-[#7A6A58] max-w-2xl mb-10">
      Cafés de especialidad, pastelería casera y algo salado para acompañar.
      Todo pensado para disfrutar sin prisas.
    </p>

    <!-- Layout principal -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <div class="md:col-span-2 flex flex-col gap-6">
        <div v-for="category in categories" :key="category.id" class="bg-white p-6 rounded-xl shadow">

          <h2 class="text-2xl font-bold text-[#6B3F27] mb-4">
            {{ category.name }}
          </h2>

          <!-- Productos -->
          <div v-for="product in category.products" :key="product.id"
               class="flex items-center justify-between py-4 border-b last:border-none">

            <div>
              <h3 class="text-lg font-semibold text-[#5A4333]">
                {{ product.name }}
              </h3>

              <p class="text-sm text-gray-600 mb-2">
                {{ product.description }}
              </p>

              <span v-if="product.tag"
                    class="px-3 py-1 rounded-full text-xs font-bold border border-[#D7B7A0] text-[#AD6F52]">
                {{ product.tag }}
              </span>
            </div>

            <div class="flex items-center gap-3">
              <p class="font-semibold text-[#5A4333]">
                {{ product.price }}€
              </p>

              <img v-if="product.image"
                   :src="product.image"
                   class="w-20 h-20 object-cover rounded-lg shadow" />
            </div>

          </div>

        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-6 h-fit">

        <h3 class="text-sm text-[#A87C5A] font-bold tracking-wide mb-2">
          RECOMENDACIÓN DE LA CASA
        </h3>

        <img v-if="recommended?.image"
             :src="recommended.image"
             class="rounded-xl mb-4" />

        <h2 class="text-2xl font-bold text-[#6B3F27]">
          {{ recommended?.name || 'Sin recomendación' }}
        </h2>

        <p class="text-gray-600 mt-2">
          {{ recommended?.description }}
        </p>

        <p v-if="recommended"
           class="font-semibold mt-4 text-[#5A4333]">
          {{ recommended.price }}€
        </p>

      </div>
    </div>

  </section>
</template>

<style>
body {
  font-family: 'Inter', sans-serif;
}
</style>