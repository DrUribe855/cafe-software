<script setup>
import PermissionForm from '../../../components/Dashboard/Permissions/Employee/PermissionForm.vue';
import PermissionsList from '../../../components/Dashboard/Permissions/Employee/PermissionsList.vue';

</script>

<template>
    <div class="flex">
        <div class="w-1/2 h-full bg-white m-[1.5em] rounded-lg pb-[2em] shadow-lg">
            <PermissionForm/>
        </div>
        <div class="w-1/2 h-full bg-white m-[1.5em] rounded-lg pb-[2em]">
            <PermissionsList/>
        </div>
    </div>
</template>