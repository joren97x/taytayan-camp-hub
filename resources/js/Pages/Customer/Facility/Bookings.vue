<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    bookings: Object,
})

</script>

<template>
    <div>
        <h1>bookings</h1>
        {{ bookings }}
        <hr>
    </div>
</template>