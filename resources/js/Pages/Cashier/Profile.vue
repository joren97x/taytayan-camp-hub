<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

defineOptions({
    layout: CashierLayout
})

</script>

<template>
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div>
</template>
