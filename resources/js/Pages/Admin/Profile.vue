<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    <div class="q-pa-md">
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div>
</template>
