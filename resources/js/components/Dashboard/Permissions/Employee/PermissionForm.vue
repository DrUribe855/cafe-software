<script setup>
import { Send } from "lucide-vue-next";
import { useLeaveRequests } from '../../../../composables/Leave/useLeaveRequests';

const { permission, errors, touched, sendPermissionRequest } = useLeaveRequests();


</script>

<template>
    <div class="mt-4 ml-3 p-4 pb-4 mb-0 mr-3">
        <!-- Encabezado formulario -->
        <section>
            <div class="flex items-center">
                <Send class="w-10 h-7 md:h-5"/>
                <h1 class="ml-2 text-3xl md:text-2xl">Nueva solicitud</h1>
            </div>
            <div class="mt-2">
                <p class="text-md md:text-sm text-gray-600">Completa el formulario para solicitar un permiso o vacaciones</p>
            </div>
        </section>

        <!-- Cuerpo del formulario -->
        <form @submit.prevent="sendPermissionRequest">
            <div class="mt-5">
                <div class="flex flex-col">
                    <label for="type" class="text-lg md:text-[1em]">Tipo de solicitud</label>
                    <select
                        name="type"
                        id="type"
                        class="w-full sm:w-auto md:w-auto p-2 mt-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
                        v-model="permission.type"
                        @blur = "touched.type = true"
                    >
                        <option value="" disabled selected>Selecciona el tipo de solicitud</option>
                        <option value="Permiso">Permiso</option>
                        <option value="Vacaciones">Vacaciones</option>
                    </select>
                    <span v-if="touched.type && errors.type" class="text-red-500 mt-2 font-sm">{{ errors.type }}</span>
                </div>
                <div class="flex mt-4">
                    <div class="flex flex-col w-1/2 p-2 pl-0">
                        <label for="start-date" class="text-lg md:text-[1em]">Fecha de inicio</label>
                        <input type="date" name="start-date" id="start-date" class="mt-2 border border-gray-300 rounded-lg p-2" v-model="permission.startDate" @blur="touched.startDate = true">
                        <span v-if="touched.startDate && errors.startDate" class="text-red-500 mt-2 font-sm"> {{ errors.startDate }}</span>
                    </div>
                    <div class="flex flex-col w-1/2 p-2 pl-0">
                        <label for="end-date" class="text-lg md:text-[1em]">Fecha de fin</label>
                        <input type="date" name="end-date" id="end-date" class="mt-2 border border-gray-300 rounded-lg p-2" v-model="permission.endDate" @blur="touched.endDate = true">
                        <span v-if="touched.endDate && errors.endDate" class="text-red-500 mt-2 font-sm"> {{ errors.endDate }}</span>
                    </div>
                </div>
                <div class="flex flex-col mt-4" v-if="permission.type !== 'Vacaciones'">
                    <label for="reason" class="text-lg md:text-[1em]" >Motivo de la solicitud</label>
                    <textarea rows="5" name="reason" id="reason" placeholder="Describe brevemente el motivo de la solicitud..." class="mt-2 border border-gray-300 rounded-lg p-2" v-model="permission.reason" @blur="touched.reason = true"></textarea>
                    <span v-if="touched.reason && errors.reason" class="text-red-500 mt-2 font-sm">{{ errors.reason }}</span>
                </div>
                <div class="flex items-center mt-4">
                    <button type="submit" class="w-full rounded-lg p-3 flex justify-center bg-sky-500 hover:bg-sky-600 text-white"><Send class="mr-2"/> <span class="text-lg md:text-[1em]">Enviar solicitud</span></button>
                </div>
            </div>
        </form>
    </div>
</template>
