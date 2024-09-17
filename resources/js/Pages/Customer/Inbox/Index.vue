<script setup>

import { useQuasar } from 'quasar'
import { usePage, Link } from '@inertiajs/vue3'
import ChatLayout from '@/Layouts/ChatLayout.vue';

const $page = usePage()
defineProps({
    conversations: Object
})

defineOptions({
    layout: ChatLayout
})

const receiver = (participants) => {
    return participants.find((participant) => participant.id != $page.props.auth.user.id)
}

</script>

<template>
    <q-list>
        <Link v-for="conversation in conversations" :href="route('conversations.show', conversation.id)">
            <q-item>
                <q-item-section>
                    {{ conversation.id }}
                    {{ receiver(conversation.participants).first_name }}
                </q-item-section>
            </q-item>
        </Link>
    </q-list>
</template>