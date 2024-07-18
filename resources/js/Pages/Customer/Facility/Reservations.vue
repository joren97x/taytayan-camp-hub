<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    <div>
        <h1>Reservations</h1>
    </div>
</template>