<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3';
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    <Head title="Profile" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div>
</template>
