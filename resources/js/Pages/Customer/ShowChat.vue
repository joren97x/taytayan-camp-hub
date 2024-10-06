<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    conversations: Object,
    conversation: Object
})

</script>

<template>
    <Index :conversations="conversations">
        <Show :conversation="conversation" />
    </Index>
</template>
