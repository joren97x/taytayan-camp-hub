<script setup>

import { Head } from '@inertiajs/vue3'
import FacilityLayout from '@/Layouts/FacilityLayout.vue'

defineOptions({
    layout: FacilityLayout
})

const props = defineProps({
    facility: Object
})

</script>

<template>
    
    <Head title="Reviews" />
    {{ facility }}
</template>