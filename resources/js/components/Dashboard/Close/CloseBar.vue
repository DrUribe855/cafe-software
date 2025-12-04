<script setup>
import { h, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useEstablishmentStore } from '../../../stores/establishmentStore'
import { alert } from '../../../composables/Pastrie/alert'
const props = defineProps({
  date: String,
  role: String,
  fridge: Number,
  temperature: [String, Number],
  establishmentId: Number
})

const emit = defineEmits(['update:date', 'update:fridge', 'update:temperature'])
const establishmentStore = useEstablishmentStore()
const router = useRouter()

const selectedFridge = ref(props.fridge || '')
const localTemp = ref(props.temperature || '')
const fridges = ref([])
const note = ref('')

const fetchFridges = async () => {
  try {
    let establishmentId = establishmentStore.getCode() || props.establishmentId

    if (!establishmentId) {
      fridges.value = []
      return
    }

    const { data } = await axios.get(`/api/refrigerators/establishment/${establishmentId}`)

    fridges.value = (data || []).map(f => ({
      id: f.value,
      name: f.label,
      note: f.note || ''
    }))
  } catch (error) {
    console.error('Error al obtener lista de neveras:', error.response?.data || error)
  }
}

onMounted(fetchFridges)

watch(
  () => establishmentStore.code,
  (newCode, oldCode) => {
    if (newCode && newCode !== oldCode) {
      selectedFridge.value = ''
      note.value = ''
      fetchFridges()
    }
  },
  { immediate: true }
)

watch(selectedFridge, val => {
  emit('update:fridge', val)
  const fridge = fridges.value.find(f => f.id === val)
  note.value = fridge ? fridge.note || '' : ''
})

watch(localTemp, val => emit('update:temperature', val))

const updateNote = async () => {
  if (!selectedFridge.value) {
    alert('Debes seleccionar una nevera antes de agregar una nota', '', 'warning')
    return
  }

  if (!note.value.trim()) {
    alert('La nota está vacía', '', 'warning')
    return
  }

  try {
    await axios.put(`/api/refrigerators/${selectedFridge.value}/note`, { note: note.value })
    const fridge = fridges.value.find(f => f.id === selectedFridge.value)
    if (fridge) fridge.note = note.value
    alert('La nota ha sido actualizada con éxito', '', 'success')
  } catch (error) {
    alert('Error al actualizar la nota', '', 'error')
  }
}

const goBack = () => router.back()


</script>

<template>
  <button
    @click="goBack"
    class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition"
  >
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
    <span class="hidden md:inline text-sm font-medium">Volver</span>
  </button>

  <section class="flex flex-col gap-4 p-4 mt-3 bg-white shadow-sm rounded-lg mb-4">

    <div class="flex flex-col md:flex-row md:justify-between w-full">
      <h1 class="text-lg md:text-xl font-bold text-slate-800 border-l-4 border-sky-400 pl-3">
        Gestión de cierre
      </h1>

      <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-3 md:mt-0">
        <div v-if="role === 'admin'">
          <label class="text-base font-semibold text-slate-700">Fecha:</label>
          <input
            type="date"
            class="w-full sm:w-auto p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
            :value="date"
            @change="e => emit('update:date', e.target.value)"
          />
        </div>
      </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-start md:gap-6 w-full">

      <div class="flex flex-col gap-2 w-full md:w-1/2">
        <label class="text-sm font-semibold text-slate-700">
          Seleccionar nevera:
        </label>

        <select
          v-model="selectedFridge"
          class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 hover:border-sky-400 transition"
        >
          <option disabled value="">Seleccionar nevera</option>
          <option
            v-for="fridge in fridges"
            :key="fridge.id"
            :value="fridge.id"
            class="bg-white text-gray-800"
          >
            {{ fridge.name }}
          </option>
        </select>

        <div v-if="selectedFridge" class="w-full">

          <div
            v-if="role === 'admin'"
            class="flex flex-col gap-2 bg-white border-l-4 border-sky-400 p-3 rounded w-full"
          >
            <label class="text-sm font-semibold text-gray-700">Nota para esta nevera:</label>

            <textarea
              v-model="note"
              rows="3"
              class="p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
              placeholder="Indicaciones sobre esta nevera..."
            ></textarea>

            <button
              @click="updateNote"
              class="self-start bg-sky-500 hover:bg-sky-400 text-white font-medium px-3 py-1 rounded transition"
            >
              Guardar nota
            </button>
          </div>

          <p
            v-else
            class="text-sm text-gray-700 bg-sky-50 border-l-4 border-sky-400 p-2 rounded mt-1 w-full text-left"
          >
            <strong>Nota: </strong>
            {{ fridges.find(f => f.id === selectedFridge)?.note || 'No hay nota disponible' }}
          </p>
        </div>

        <div v-if="role === 'employee'" class="flex flex-col gap-2 w-full sm:w-52 mt-2">
          <label class="text-sm font-semibold text-slate-700">
            Temperatura final (°C):
          </label>

          <input
            type="number"
            v-model="localTemp"
            placeholder="Temperatura en °C"
            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 hover:border-sky-400 transition"
          />
        </div>
      </div>

    </div>

  </section>
</template>
