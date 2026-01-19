<script setup>
import { Send } from "lucide-vue-next";
import { useUserLeaveRequests } from '../../../../composables/Leave/Employee/useUserLeaveRequests';

const { request, errors, touched, sendLeaveRequest } = useUserLeaveRequests();
</script>

<template>
    <div class="m-2 sm:m-3 p-3 sm:p-4">
        <!-- Encabezado formulario -->
        <section>
            <div class="flex items-center gap-2">
                <Send class="w-6 h-6 md:w-8 md:h-8"/>
                <h1 class="text-base md:text-lg lg:text-2xl font-semibold">Nueva solicitud</h1>

            </div>
            <div class="mt-3">
                <p class="text-sm md:text-md lg:text-md text-gray-600">Completa el formulario para solicitar un permiso o vacaciones</p>
            </div>
        </section>

        <!-- Cuerpo del formulario -->
        <form @submit.prevent="sendLeaveRequest">
            <div class="mt-5">
                <div class="flex flex-col">
                    <label for="type" class="text-md md:text-[1em]">Tipo de solicitud</label>
                    <select
                        name="type"
                        id="type"
                        class="w-full text-sm md:text-lg sm:w-auto md:w-auto p-2 mt-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition"
                        v-model="request.type"
                        @blur = "touched.type = true"
                    >
                        <option value="" disabled selected>Selecciona el tipo de solicitud</option>
                        <option value="Permiso">Permiso</option>
                        <option value="Vacaciones">Vacaciones</option>
                    </select>
                    <span v-if="touched.type && errors.type" class="text-red-500 mt-2 text-sm">{{ errors.type }}</span>
                </div>
                <div class="flex flex-col md:flex-row mt-4 gap-4">
                    <div class="flex flex-col w-full md:w-1/2 gap-1">
                        <label for="start-date" class="text-sm md:text-[1em]">Fecha de inicio</label>
                        <input type="date" name="start-date" id="start-date" class="mt-2 border border-gray-300 rounded-lg p-2" v-model="request.startDate" @blur="touched.startDate = true">
                        <span v-if="touched.startDate && errors.startDate" class="text-red-500 mt-2 text-sm"> {{ errors.startDate }}</span>
                    </div>
                    <div class="flex flex-col w-full md:w-1/2 gap-1">
                        <label for="end-date" class="text-sm md:text-[1em]">Fecha de fin</label>
                        <input type="date" name="end-date" id="end-date" class="mt-2 border border-gray-300 rounded-lg p-2" v-model="request.endDate" @blur="touched.endDate = true">
                        <span v-if="touched.endDate && errors.endDate" class="text-red-500 mt-2 text-sm"> {{ errors.endDate }}</span>
                    </div>
                </div>
                <div class="flex flex-col mt-4" v-if="request.type !== 'Vacaciones'">
                    <label for="reason" class="text-sm md:text-[1em]" >Motivo de la solicitud</label>
                    <textarea rows="4" name="reason" id="reason" placeholder="Describe brevemente el motivo de la solicitud..." class="mt-2 border border-gray-300 rounded-lg p-2" v-model="request.reason" @blur="touched.reason = true"></textarea>
                    <span v-if="touched.reason && errors.reason" class="text-red-500 mt-2 text-sm">{{ errors.reason }}</span>
                </div>
                <div class="flex items-center mt-4">
                    <button type="submit" class="w-full rounded-lg p-3 flex justify-center bg-sky-500 hover:bg-sky-600 text-white"><Send class="mr-2"/> <span class="text-lg md:text-[1em]">Enviar solicitud</span></button>
                </div>
            </div>
        </form>
    </div>
</template>
