<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: CashierLayout
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
