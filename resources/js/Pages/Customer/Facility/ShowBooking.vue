<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import FacilityCard from './Partials/FacilityCard.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    booking: Object
})

</script>

<template>
    <Head title="Facilities" />
    {{booking}}
</template>
