<script setup>
import MenuUploaderImage from './MenuUploaderImage.vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    category: {
        type: Object,
        required: true
    },
    isEditing: {
        type: Boolean,
        required: true
    },
    file: {
        type: File,
        required: false
    },
    isSelected: {
        type: Boolean,
        required: true
    },
    selectedFile: {
        type: Function,
        required: true,
    }
});


const emits = defineEmits(['add-custom-tag', 'remove-custom-tag', 'save-product']);


</script>
<template>

    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-3 md:mt-0">
        <label class="text-base font-semibold text-slate-700">Seleccionar categoría:</label>
        <select
            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
            v-model="category.category_id"
        >
            <option disabled value="">Seleccionar categoría</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
            </option>
        </select>
    </div>

    <div class="flex-1">
        <h2 class="text-base font-semibold text-slate-700 mb-3">
            {{ isEditing ? "Editar producto" : "Añadir nuevo producto" }}
        </h2>

        <div class="flex flex-col gap-3">
            <form @submit.prevent="emits('save-product')">
                <input
                    type="text"
                    v-model="category.name"
                    placeholder="Nombre"
                    class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                />
                <textarea
                    v-model="category.description"
                    placeholder="Descripción"
                    class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                />

                <input
                    type="number"
                    v-model="category.price"
                    placeholder="Precio (€)"
                    class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                />

                <select
                    v-model="category.tag"
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
                            v-model="category.new_tag"
                            @keyup.enter="emits('add-custom-tag')"
                            placeholder="Ej: Contiene maní"
                            class="flex-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                        />

                        <button
                            type="button"
                            @click="emits('add-custom-tag')"
                            class="px-3 rounded-lg bg-sky-500 text-white hover:bg-sky-400 transition"
                        >
                            Añadir
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-2">
                        <span
                            v-for="(tag, index) in category.custom_tags"
                            :key="index"
                            class="flex items-center gap-2 bg-sky-100 text-sky-700 text-xs px-3 py-1 rounded-full"
                        >
                            {{ tag }}
                            <button
                                type="button"
                                @click="emits('remove-custom-tag', index)"
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
                        <input type="checkbox" v-model="category.recommended" class="sr-only" />
                        <div
                            class="w-10 h-5 bg-gray-300 rounded-full transition"
                            :class="category.recommended ? 'bg-sky-500' : 'bg-gray-300'"
                        ></div>
                        <div
                            class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition"
                            :class="category.recommended ? 'translate-x-5' : ''"
                        ></div>
                    </div>
                </label>

                <MenuUploaderImage
                    :file="file"
                    :isSelected="isSelected"
                    :selected-file="selectedFile"
                />

                <button
                    type="submit"
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
            </form>







        </div>
    </div>

</template>
