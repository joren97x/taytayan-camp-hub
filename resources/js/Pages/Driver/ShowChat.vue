<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: DriverLayout
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
