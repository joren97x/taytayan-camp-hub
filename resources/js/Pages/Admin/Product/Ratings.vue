<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout
})

defineProps({
    ratings: Object
})

</script>

<template>
    
    <Head title="Reviews" />
    <h1>Reviews</h1>
    {{ ratings }}
</template>