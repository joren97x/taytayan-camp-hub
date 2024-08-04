<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    facility: Object
})

</script>

<template>
    
    <Head title="Reviews" />
    {{ facility }}
</template>