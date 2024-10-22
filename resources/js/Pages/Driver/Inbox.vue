<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: DriverLayout
})

defineProps({
    conversations: Object
})

</script>

<template>
    <Head title="Inbox" />
     <Index :conversations="conversations" />
</template>
