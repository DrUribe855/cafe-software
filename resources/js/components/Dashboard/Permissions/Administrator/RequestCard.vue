<script setup>
import { ref, computed } from 'vue';

const emit = defineEmits(['close', 'sendResponse']);

const { request } = defineProps({
    request: {
        type: Object,
        required: true,
    }
});

const comment = ref(request.comments);
const readOnly = computed(() => request.status !== 'Pendiente');
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
                    {{ request.user.name }}
                </p>
                <p class="text-sm text-slate-500">
                    {{ request.user.department }}
                </p>

                <span
                    class="inline-block mt-2 px-3 py-1 text-xs rounded-full font-medium"
                    :class="{
                        'bg-yellow-200 text-yellow-700': request.status === 'Pendiente',
                        'bg-green-200 text-green-700': request.status === 'Aprobado',
                        'bg-red-200 text-red-700': request.status === 'Rechazado'
                    }"
                >
                    {{ request.status }}
                </span>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-3">

                <!-- Tipo / Fecha -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Tipo de Solicitud</p>
                        <p class="font-medium text-slate-800">
                            {{ request.type }}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de Solicitud</p>
                        <p class="font-medium text-slate-800">
                            <!-- {{ new Date(request.created_at).toLocaleDateString('es-ES')}} -->
                              {{ new Date(request.created_at).toISOString().slice(0, 10).split('-').join('-') }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de inicio</p>
                        <p class="font-medium text-slate-800">
                            {{ request.start_date }}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de fin</p>
                        <p class="font-medium text-slate-800">
                            {{ request.end_date }}
                        </p>
                    </div>
                </div>

                <!-- Motivo -->
                <div v-if="request.reason" class="bg-slate-100 rounded-lg p-3">
                    <p class="text-xs text-slate-500">Motivo</p>
                    <p class="text-slate-700 text-xs/6">
                        {{ request.reason }}
                    </p>
                </div>

                <!-- Información de aprobación -->
                <div v-if="request.status !== 'Pendiente'" class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Respuesta dada por</p>
                        <p class="text-xs/5 text-slate-800">
                            {{ request.approver.name }}
                        </p>
                    </div>

                    <div class="bg-slate-100 rounded-lg p-3">
                        <p class="text-xs text-slate-500">Fecha de respuesta</p>
                        <p class="font-medium text-slate-800">
                            {{ request.approved_at }}
                        </p>
                    </div>
                </div>

                <!-- Comentarios -->
                <div>
                    <label class="text-xs text-slate-500">
                        Observación / Comentario
                    </label>
                    <textarea
                        v-model="comment"
                        :readonly="readOnly"
                        rows="3"
                        placeholder="Escribe un comentario (opcional)"
                        class="w-full mt-1 p-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-200"
                    ></textarea>
                </div>
            </div>

            <!-- Acciones del modal -->
            <div v-if="request.status === 'Pendiente'" class="flex gap-3 p-4 border-t">
                <button
                    @click="emit('sendResponse', comment, 'Rechazado', request.id)"
                    class="flex-1 py-2 rounded-lg border border-red-400 text-red-500 hover:bg-red-50 transition"
                >
                    ✕ Rechazar
                </button>

                <button
                    @click="emit('sendResponse', comment, 'Aprobado', request.id)"
                    class="flex-1 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition"
                >
                    ✓ Aprobar
                </button>
            </div>
        </div>
    </div>
</template>
