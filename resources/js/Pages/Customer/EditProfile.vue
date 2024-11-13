<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    google_maps_api_key: String
})
</script>

<template>
    <Head title="Profile" />
    <div class="q-pa-md">
        <UpdateProfileInformationForm :google_maps_api_key="google_maps_api_key" />
        <UpdatePasswordForm/>
    </div>
</template>
