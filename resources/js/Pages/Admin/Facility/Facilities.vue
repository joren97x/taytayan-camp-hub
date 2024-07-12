<script setup>

import { Head } from '@inertiajs/vue3'
import FacilityLayout from '@/Layouts/FacilityLayout.vue'

defineOptions({
    layout: FacilityLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    
    <Head title="Reviews" />
    <h1>Facilities</h1>
    {{ facilities }}
</template>