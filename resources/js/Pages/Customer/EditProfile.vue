<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue'
import Profile from './Profile.vue'

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
    <Profile>
        <q-card flat>
            <q-card-actions class="text-center justify-center items-center flex lt-md">
                <Link :href="route('customer.profile')" class="lt-md">
                    <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                </Link>
                <div class="text-h6">Profile</div>
            </q-card-actions>
            <UpdateProfileInformationForm :google_maps_api_key="google_maps_api_key" />
            <UpdatePasswordForm/>
        </q-card>
    </Profile>
</template>
