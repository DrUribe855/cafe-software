<script setup>
import PermissionForm from '../../../components/Dashboard/Permissions/Employee/PermissionForm.vue';
import PermissionsList from '../../../components/Dashboard/Permissions/Employee/PermissionsList.vue';
import CalendarTemplate from '../../../components/Dashboard/Permissions/Administrator/CalendarTemplate.vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../../stores/userStore';

const router = useRouter();
const store = useUserStore();

const goBack = () => {
    router.back();
}
</script>

<template>
    <div>
        <div>
            <button @click="goBack" class="flex items-center gap-2 bg-sky-400 text-white px-4 py-3 rounded-lg shadow hover:bg-sky-300 transition ml-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span class="hidden md:inline text-sm font-medium">Volver</span>
            </button>
        </div>
        <div v-if="store.user.role !== 'admin'"class="flex flex-col md:flex-row justify-center">
            <section class="md:w-1/2 bg-white m-[1.5em] md:m-[1em] rounded-xl mb-0 shadow-lg">
                <PermissionForm/>
            </section>
            <section class="md:w-1/2 bg-white m-[1.5em] md:m-[1em] rounded-xl mb-0 shadow-lg">
                <PermissionsList/>
            </section>
        </div>
        <div>
            <CalendarTemplate/>
        </div>
    </div>
</template>
