<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import Chat from '@/Components/Chat.vue'

defineOptions({
    layout: AdminLayout
})


</script>

<template>
    <Head title="Dashboard" />
    <Chat/>
</template>