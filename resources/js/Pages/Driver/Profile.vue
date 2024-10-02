<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({
    layout: DriverLayout
})



</script>

<template>
    <div class="q-pa-md">
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div>
</template>
