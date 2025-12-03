<script setup>
import PermissionForm from '../../../components/Dashboard/Permissions/Employee/PermissionForm.vue';
import PermissionsList from '../../../components/Dashboard/Permissions/Employee/PermissionsList.vue';

</script>

<template>
    <div class="flex items-start">
        <div class="w-1/2 bg-white m-[1.5em] rounded-lg shadow-lg">
            <PermissionForm/>
        </div>
        <div class="w-1/2 bg-white m-[1.5em] rounded-lg shadow-lg">
            <PermissionsList/>
        </div>
    </div>
</template>
