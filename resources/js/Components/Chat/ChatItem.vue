<script setup>

import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { usePage, router } from '@inertiajs/vue3'
import { useConversationStore } from '@/Stores/ConversationStore';

const conversationStore = useConversationStore()
const props = defineProps({ conversation: Object })
const $page = usePage()
const receiver = computed(() => {
    return props.conversation.participants.find((participant) => participant.id != $page.props.auth.user.id)
})

const onClick = () => {
    conversationStore.fetchConversation(props.conversation.id)
}

</script>

<template>
    <Link :href="route('conversations.show', conversation.id)">
        <q-item 
            clickable 
            @click="onClick()"
            class="rounded-borders" 
            :active="$page.url[$page.url.length-1] == conversation.id" 
            active-class="bg-grey text-white"
        >
            <q-item-section avatar>
                <q-avatar color="primary" class="text-capitalize" text-color="white">
                    <q-img v-if="receiver.profile_pic" :src="`/storage/${receiver.profile_pic}`" class="fit" fit="cover"></q-img>
                    <div v-else>{{ receiver.first_name[0] }}</div>
                </q-avatar>
            </q-item-section>
            <q-item-section>
                <!-- {{ conversation.user.first_name + ' ' + conversation.user.last_name }} -->
                {{ receiver.first_name + ' ' + receiver.last_name }}
            </q-item-section>
            <q-item-section side>
                <q-icon name="circle" color="green" size="10px" v-if="receiver.is_online"/>
            </q-item-section>
        </q-item>
    </Link>
</template>