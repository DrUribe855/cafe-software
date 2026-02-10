<script setup>
const emit = defineEmits(['close']);

const props = defineProps({
    absence: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-xl w-full max-w-md overflow-hidden relative">

            <!-- Botón cerrar -->
            <button
                @click="emit('close')"
                class="absolute top-3 right-3 text-slate-400 hover:text-slate-600 transition"
            >
                ✕
            </button>

            <!-- Header -->
            <div class="p-4 bg-slate-10">
                <p class="font-semibold text-slate-800">
                    {{ absence.user.name }}
                </p>
                <p class="text-sm text-slate-500">
                    {{ absence.user.department }}
                </p>

                <span
                    class="inline-block mt-2 px-3 py-1 text-xs rounded-full font-medium bg-gray-200 text-gray-700"
                >
                    Ausente - {{ absence.type }}
                </span>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-3">

                <!-- Tipo / Fecha de solicitud -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Tipo de Solicitud</p>
                        <p class="font-medium text-slate-800">
                            {{ absence.type }}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Estado</p>
                        <p class="font-medium text-slate-800">
                            {{ absence.leave.status }}
                        </p>
                    </div>
                </div>

                <!-- Fechas de inicio y fin -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de inicio</p>
                        <p class="font-medium text-slate-800">
                            {{ new Date(absence.leave.start_date).toISOString().slice(0, 10).split('-').join('-')}}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de fin</p>
                        <p class="font-medium text-slate-800">
                            {{ new Date(absence.leave.end_date).toISOString().slice(0, 10).split('-').join('-')}}
                        </p>
                    </div>
                </div>

                <!-- Motivo -->
                <div v-if="absence.leave.reason" class="bg-slate-100 rounded-lg p-3">
                    <p class="text-xs text-slate-500">Motivo</p>
                    <p class="text-slate-700 text-xs/6">
                        {{ absence.leave.reason }}
                    </p>
                </div>

                <!-- Información de aprobación -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Aprobado por</p>
                        <p class="text-xs/5 text-slate-800">
                            {{ absence.approver }}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de aprobación</p>
                        <p class="font-medium text-slate-800">
                            {{ new Date(absence.approved_at).toISOString().slice(0, 10).split('-').join('-')}}
                        </p>
                    </div>
                </div>

                <!-- Comentarios -->
                <div v-if="absence.leave.comments" class="bg-slate-100 rounded-lg p-3">
                    <p class="text-xs text-slate-500">Observación / Comentario</p>
                    <p class="text-slate-700 text-xs/6">
                        {{ absence.leave.comments }}
                    </p>
                </div>
            </div>

            <!-- Botón de cerrar -->
            <div class="flex gap-3 p-4 border-t">
                <button
                    @click="emit('close')"
                    class="flex-1 py-2 rounded-lg bg-slate-500 text-white hover:bg-slate-600 transition"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>
