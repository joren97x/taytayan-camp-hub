<script setup>
import { useQuasar } from 'quasar'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

const props = defineProps({
    conversation: Object
})

const $q = useQuasar()
const $page = usePage()
const form = useForm({
    message: ''
})

function sendMessage() {
    // if(currentConversation.value.conversation) {
        // console.log('SEND A MESASGE')
        form.post(route(`message.store`, props.conversation.id), {
            onSuccess: () => {
                form.reset()
                $q.notify('annyeong')
            }
        })
    // }
    // else {
    //     console.log('GO CREATE A CONVERSATION AND SEND A MESASGE')
    //     form.post(route(`conversation.store`, currentConversation.value.user.id), {
    //         onSuccess: () => {
    //             form.reset()
    //             $q.notify('annyeong')
    //         }
    //     })
    // }
}

const receiver = computed(() => {
    return props.conversation.participants.find((participant) => participant.id != $page.props.auth.user.id)
})

const conversationId = computed(() => {
    return props.conversation.id
})
Echo.private(`conversation.${conversationId.value}`)
    .listen('MessageSent', (data) => {
        console.log(data)
    })
    Echo.leaveChannel(`conversation.${conversationId.value}`)

// console.log(conversationId.value)
// watch(conversationId, () => {
//     Echo.leaveChannel(`conversation.${conversationId.value}`)
//     Echo.private(`conversation.${conversationId.value}`)
//     .listen('MessageSent', (data) => {
//         console.log(data)
//     })
// })


</script>

<template>
    <q-toolbar class="bg-primary text-white">
        <q-avatar class="q-mr-md">
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
        </q-avatar>
        {{ receiver.first_name + ' ' + receiver.last_name }}
    </q-toolbar>

    <div class="q-pa-md bg-blue-2">
        <div v-if="conversation.messages.length > 0">
            <q-chat-message
                v-for="message in conversation.messages"
                name="me"
                avatar="https://cdn.quasar.dev/img/avatar1.jpg"
                :text="[message.message]"
                :sent="$page.props.auth.user.id == message.user_id"
            />
        </div>
        <!-- <p class="text-center" v-if="conversation == null">To start a conversation send them a message.</p> -->
        <q-form @submit="sendMessage()">
            <q-input label="Send a message..." filled v-model="form.message">
                <template v-slot:append>
                    <q-btn 
                        icon="send" 
                        type="submit" 
                        unelevated 
                        color="primary" 
                        round
                        @click="sendMessage()"
                        :loading="form.processing"
                        :disable="form.processing"
                    />
                </template>
            </q-input>
        </q-form>
    </div>
</template>