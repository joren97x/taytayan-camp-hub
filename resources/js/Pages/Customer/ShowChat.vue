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
    <q-card class="q-mt-md">
        <Index :conversations="conversations" v-if="$q.screen.gt.sm">
            <Show :conversation="conversation" />
        </Index>
        <Show :conversation="conversation" v-else/>
    </q-card>
</template>
