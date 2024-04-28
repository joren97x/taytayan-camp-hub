<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    <Head title="Dashboard" />
    <div class="text-h1">
        Admin Dashboard
    </div>
</template>