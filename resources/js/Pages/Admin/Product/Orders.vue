<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/ProductLayout.vue'

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    
    <Head title="Orders" />
    <MilkteaLayout>
        <h1>Orders</h1>
    </MilkteaLayout>
</template>