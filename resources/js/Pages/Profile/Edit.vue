<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'

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
    
    <!-- <UpdateProfileInformationForm
        :must-verify-email="mustVerifyEmail"
        :status="status"
        class="max-w-xl"
    />
    <UpdatePasswordForm class="max-w-xl" />
    <DeleteUserForm class="max-w-xl" /> -->
    
</template>
