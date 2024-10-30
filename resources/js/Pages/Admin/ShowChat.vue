<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: AdminLayout
})

defineProps({
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
