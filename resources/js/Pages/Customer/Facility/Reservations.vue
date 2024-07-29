<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    reservations: Object,
})

</script>

<template>
    <div>
        <h1>Reservations</h1>
        {{ reservations }}
        <hr>
    </div>
</template>