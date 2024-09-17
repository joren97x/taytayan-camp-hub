<script setup>

import { useQuasar } from 'quasar'
import { useForm, usePage } from '@inertiajs/vue3'
import ChatLayout from '@/Layouts/ChatLayout.vue'
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

defineOptions({
    layout: ChatLayout
})

const props = defineProps({
    selected_user: Object
})

const receiver = ref(props.selected_user)

const scrollArea = ref(null)
// const messages = currentConversation.value.messages

// Watch for changes in props.conversation.messages and update messages accordingly

const $q = useQuasar()
const $page = usePage()
const form = useForm({
    message: '',
    receiver_id: receiver.value.id
})

function sendMessage() {
    form.post(route(`conversations.store`), {
        onSuccess: () => {
            form.reset()
            $q.notify('annyeong')
        }
    })
}

// function addMessage(message) {
//     console.log('helo?')
//     console.log(currentConversation.value)
//     currentConversation.value.messages.push(message)
//     scrollToBottom()
// }

// function scrollToBottom() {
//     if (scrollArea.value) {
//         // scrollArea.value.setScrollPosition('end');
//         const contentHeight = scrollArea.value.$el.scrollHeight;
//         // Scroll to the bottom
//         scrollArea.value.setScrollPosition('vertical', contentHeight, 500);
//     }
// };

// const receiver = computed(() => {
//     return props.conversation.participants.find((participant) => participant.id != $page.props.auth.user.id)
// })

onMounted(() => {
    console.log('listen from onmounted')
    // subscribeChannel()
    // scrollToBottom()
})

// function subscribeChannel() {
//     Echo.private(`conversation.${currentConversation.value.id}`)
//     .listen('MessageSent', (data) => {
//         $q.notify('someone sent a message')
//         console.log(data)
//         addMessage(data.message)
//     })
// }

</script>

<template>
    <q-toolbar class="bg-primary text-white">
        <q-avatar class="q-mr-md">
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
        </q-avatar>
        {{ receiver.first_name + ' ' + receiver.last_name }}
    </q-toolbar>
    <!-- {{ currentConversation }} -->
    <div class="q-pa-md bg-blue-2">
        <!-- <div v-if="currentConversation.messages.length > 0">
            <q-scroll-area ref="scrollArea" style="height: 75vh; max-width: 100%;" class="q-pa-lg">
                <q-chat-message
                    v-for="message in currentConversation.messages"
                    name="me"
                    avatar="https://cdn.quasar.dev/img/avatar1.jpg"
                    :text="[message.message]"
                    :sent="$page.props.auth.user.id == message.user_id"
                />
            </q-scroll-area>
        </div> -->
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