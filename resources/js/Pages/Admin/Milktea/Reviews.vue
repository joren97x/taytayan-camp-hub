<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/MilkteaLayout.vue'

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    
    <Head title="Reviews" />
    <MilkteaLayout>
        <h1>Reviews</h1>
    </MilkteaLayout>
</template>