<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({
    layout: DriverLayout
})



</script>

<template>
    <div class="q-ma-xl">
        <q-avatar size="100px">
            <img src="https://cdn.quasar.dev/img/avatar.png">
        </q-avatar>
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div>
</template>
