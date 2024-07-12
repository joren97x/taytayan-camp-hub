<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import { useQuasar } from 'quasar'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    users: Object
})

const $q = useQuasar()
const tab = ref('people')
const currentConversation = ref({
    user: null,
    conversation: null
})

function getChat(user) {
    currentConversation.value.user = user
    axios.get(route('conversation.show', user.id))
    .then((res) => {
        currentConversation.value.conversation = res.data.conversation
        console.log(res)
    })
    .catch((err) => {
        console.error(err)
    })
}

const form = useForm({
    message: ''
})

function sendMessage() {
    console.log('goo??')
    if(currentConversation.value.conversation) {
        console.log('SEND A MESASGE')
        form.post(route(`message.store`, currentConversation.value.conversation.id), {
            onSuccess: () => {
                getChat(currentConversation.value.user)
                $q.notify('annyeong')
            }
        })
    }
    else {
        console.log('GO CREATE A CONVERSATION AND SEND A MESASGE')
        form.post(route(`conversation.store`, currentConversation.value.user.id), {
            onSuccess: () => {
                getChat(currentConversation.value.user)
                $q.notify('annyeong')
            }
        })
    }
}

</script>

<template>
    <Head title="Chat" />
    
    <div class="row">
        <div class="col-4">
            <q-card>
                <q-tabs
                    v-model="tab"
                    dense
                    class="text-grey"
                    active-color="primary"
                    indicator-color="primary"
                    align="justify"
                    narrow-indicator
                >
                    <q-tab name="chats" label="chats" />
                    <q-tab name="people" label="people" />
                </q-tabs>
                <q-separator />
                <q-tab-panels v-model="tab" animated>
                    <q-tab-panel name="chats">
                        <div class="text-h6">chats</div>
                        get all the conversations
                    </q-tab-panel>
                    <q-tab-panel name="people">
                        <div class="text-h6">people</div>
                        <q-list>
                            <q-item v-for="user in users" clickable @click="getChat(user)">
                                <q-item-section avatar>
                                    <q-avatar color="primary" class="text-capitalize" text-color="white">
                                        {{ user.first_name[0] }}
                                    </q-avatar>
                                </q-item-section>
                                <q-item-section>
                                    {{ user.first_name + ' ' + user.last_name }}
                                </q-item-section>
                            </q-item> 
                        </q-list>
                    </q-tab-panel>
                </q-tab-panels>
            </q-card>

        </div>
        <div class="col-8 q-pb-md">
            <q-toolbar class="bg-primary text-white">
                <q-avatar>
                    <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
                </q-avatar>
                change this to name
            </q-toolbar>

            <div class="q-pa-md bg-grey-4">
                <div v-if="currentConversation.conversation">
                    {{ currentConversation }}
                    <q-chat-message
                        v-for="message in currentConversation.conversation.messages"
                        name="me"
                        avatar="https://cdn.quasar.dev/img/avatar1.jpg"
                        :text="[message.message]"
                        sent
                        bg-color="primary"
                        text-color="white"
                    />
                    <q-chat-message
                        name="Jane"
                        avatar="https://cdn.quasar.dev/img/avatar2.jpg"
                        :text="['doing fine, how r you?']"
                    />
                </div>
                <p class="text-center" v-if="currentConversation.conversation == null">To start a conversation send them a message.</p>
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
        </div>
    </div>

    if wala pay conversation inig send sa message dapat mo create ug conversation and then pwede na maka send ug message
    <br>
    maghimo ug specific method sa controller para ani
    
</template>