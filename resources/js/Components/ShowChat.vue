<script setup>

import { useQuasar } from 'quasar'
import { useForm, usePage } from '@inertiajs/vue3'
import ChatLayout from '@/Layouts/ChatLayout.vue'
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

defineOptions({
    layout: ChatLayout
})

const props = defineProps({
    conversation: Object
})

const currentConversation = ref(null)
const isListening = ref(false)
const scrollArea = ref(null)
// const messages = currentConversation.value.messages

// Watch for changes in props.conversation.messages and update messages accordingly
watch(() => props.conversation,
    (conversation) => {
        console.log('listen')
        if(isListening.value) {
            console.log('leave from watch')
            Echo.leave(`conversation.${currentConversation.value.id}`)
        }
        currentConversation.value = conversation;
        subscribeChannel()
        isListening.value = true
        scrollToBottom()
    },
    { immediate: true } // This ensures that the watch runs immediately with the initial value
);

const $q = useQuasar()
const $page = usePage()
const form = useForm({
    message: ''
})

function sendMessage() {
    // if(currentConversation.value.conversation) {
        // console.log('SEND A MESASGE')
        form.post(route(`messages.store`, currentConversation.value.id), {
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

function addMessage(message) {
    console.log('helo?')
    console.log(currentConversation.value)
    currentConversation.value.messages.push(message)
    scrollToBottom()
}

function scrollToBottom() {
    if (scrollArea.value) {
        // scrollArea.value.setScrollPosition('end');
        const contentHeight = scrollArea.value.$el.scrollHeight;
        // Scroll to the bottom
        scrollArea.value.setScrollPosition('vertical', contentHeight, 500);
    }
};

const receiver = computed(() => {
    return props.conversation.participants.find((participant) => participant.id != $page.props.auth.user.id)
})

onMounted(() => {
    console.log('listen from onmounted')
    // subscribeChannel()
    scrollToBottom()
})

function subscribeChannel() {
    Echo.private(`conversation.${currentConversation.value.id}`)
    .listen('MessageSent', (data) => {
        $q.notify('someone sent a message')
        console.log(data)
        addMessage(data.message)
    })
}

onUnmounted(() => {
    console.log('leave')
    Echo.leave(`conversation.${props.conversation.id}`)
})


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
        <div v-if="currentConversation.messages.length > 0">
            <q-scroll-area ref="scrollArea" style="height: 75vh; max-width: 100%;" class="q-pa-lg">
                <q-chat-message
                    v-for="message in currentConversation.messages"
                    name="me"
                    avatar="https://cdn.quasar.dev/img/avatar1.jpg"
                    :text="[message.message]"
                    :sent="$page.props.auth.user.id == message.user_id"
                />
            </q-scroll-area>
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