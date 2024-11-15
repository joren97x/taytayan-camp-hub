<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({ layout: CashierLayout })
defineProps({ google_maps_api_key: String })
</script>

<template>
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <UpdateProfileInformationForm :google_maps_api_key="google_maps_api_key"/>
        <UpdatePasswordForm/>
    </div>
</template>
