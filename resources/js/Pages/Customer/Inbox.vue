<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Index from '@/Components/Chat/Index.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    conversations: Object
})

</script>

<template>
    <Index :conversations="conversations" />
</template>
