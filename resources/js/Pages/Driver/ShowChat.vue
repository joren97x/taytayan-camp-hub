<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: DriverLayout
})

const props = defineProps({
    conversations: Object,
    conversation: Object
})

</script>

<template>
    <Index :conversations="conversations" v-if="$q.screen.gt.sm">
        <Show :conversation="conversation" />
    </Index>
    <Show :conversation="conversation" v-else />
</template>
