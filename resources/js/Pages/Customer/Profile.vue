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
})
</script>

<template>
    <Head title="Profile" />
    <UpdateProfileInformationForm/>
    <UpdatePasswordForm/>
</template>
