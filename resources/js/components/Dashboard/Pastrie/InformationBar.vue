<script setup>

const props = defineProps({
    schedule: String,
    date: String,
    role: String,
});

const emit = defineEmits(['update:schedule', 'update:date']);
console.log("Role en information bar: ", props.role);

</script>


<template>
    <section class="grid grid-cols-2 items-center">
        <!-- Columna izquierda -->
        <div>
            <h1 class="text-lg font-semibold text-slate-800">Gestión de bollería</h1>
        </div>

        <!-- Columna derecha -->
        <div class="flex justify-end items-center gap-x-4 w-full">
            <div>
                <select
                    name="schedule"
                    id="schedule"
                    class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :value="schedule"
                    @change="e => emit('update:schedule', e.target.value)"
                >
                    <option value="" selected >Seleccione un horario</option>
                    <option value="0">Apertura</option>
                    <option value="1">Cambio de turno</option>
                    <option value="2">Cierre</option>
                </select>
            </div>
            <div v-if="role === 'admin'">
                <label class="text-md font-semibold text-slate-800">Seleccione la fecha</label>
                <input
                    type="date"
                    class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :value="date"
                    @change="e => emit('update:date', e.target.value)"
                >
            </div>
        </div>
    </section>
</template>
