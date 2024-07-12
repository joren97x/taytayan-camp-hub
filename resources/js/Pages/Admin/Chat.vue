<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import ChatBox from '@/Components/Customer/ChatBox.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
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
    <Head title="Chat" />
    {{ props }}
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
                        <q-list>
                            <q-item v-for="conversation in conversations" clickable @click="router.get(route(`admin.conversation.show`, conversation.id))">
                                <!-- <q-item v-for="conversation in conversations" clickable @click="getConversation(conversation)"> -->
                                <q-item-section avatar>
                                    <q-avatar color="primary" class="text-capitalize" text-color="white">
                                        <!-- {{ conversation.user.first_name[0] }} -->
                                          J
                                    </q-avatar>
                                </q-item-section>
                                <q-item-section>
                                    <!-- {{ conversation.user.first_name + ' ' + conversation.user.last_name }} -->
                                      Name
                                </q-item-section>
                            </q-item> 
                        </q-list>
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
            <ChatBox v-if="currentConversation" :conversation="currentConversation"/>
        </div>
    </div>

    if wala pay conversation inig send sa message dapat mo create ug conversation and then pwede na maka send ug message
    <br>
    maghimo ug specific method sa controller para ani
    
</template>