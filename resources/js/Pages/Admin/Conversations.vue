<script setup>

import ChatLayout from '@/Layouts/ChatLayout.vue'
import ChatBox from '@/Components/Customer/ChatBox.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: ChatLayout
})

const props = defineProps({
    users: Object,
    conversations: Object
})

const tab = ref('chats')
const currentConversation = ref(null)

// function getChat(user) {
//     currentConversation.value.user = user
//     axios.get(route('conversation.show', user.id))
//     .then((res) => {
//         currentConversation.value.conversation = res.data.conversation
//         console.log(res)
//     })
//     .catch((err) => {
//         console.error(err)
//     })
// }

function getConversation(conversation) {
    axios.get(route('conversation.show', conversation.id))
    .then((res) => {
        currentConversation.value = res.data.conversation
        console.log(res)
    })
    .catch((err) => {
        console.error(err)
    })
}




</script>

<template>
    <div class="text-center q-mt-xl">
        You havent selected a chat...
    </div>
    
</template>