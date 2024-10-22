<script setup>

import { Head, usePage } from '@inertiajs/vue3'
import DriverLayout from '@/Layouts/DriverLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'
import { computed } from 'vue'

defineOptions({
    layout: DriverLayout
})

const $page = usePage()
const props = defineProps({
    conversations: Object,
    conversation: Object
})

const receiver = computed(() => {
    return props.conversation.participants.find((participant) => participant.id != $page.props.auth.user.id)
})

</script>

<template>
    <Head :title="receiver.first_name + ' ' + receiver.last_name" />
    <Index :conversations="conversations" v-if="$q.screen.gt.sm">
        <Show :conversation="conversation" />
    </Index>
    <Show :conversation="conversation" v-else />
</template>
