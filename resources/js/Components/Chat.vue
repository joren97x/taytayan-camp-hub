<script setup>

import { Head } from '@inertiajs/vue3'
import ShowChat from './ShowChat.vue'
import CreateConversation from './CreateConversation.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

// defineOptions({
//     layout: ChatLayout
// })

const props = defineProps({
    users: Object,
    conversations: Object,
    selected_user: Object
})

const currentConversation = ref(null)
console.log('chat . vue')
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
    axios.get(route('conversations.show', conversation.id))
    .then((res) => {
        console.log(res)
        currentConversation.value = res.data.conversation
        console.log(res)
    })
    .catch((err) => {
        console.error(err)
    })
}

const conversations = ref([])
const tab = ref('chats')

onMounted(() => {
    axios.get(route(`conversations.index`))
    .then((res) => {
        console.log(res)
        res.data.conversations.forEach((el) => {
            conversations.value.push(el)
        })
    })
    .catch((err) => {
        console.error(err)
    })
})

</script>

<template>
 
 <div class="row">
    hiiiiiiiii
    {{ selected_user }}
 </div>
    <div class="row">
            <div class="col-4">
                <q-card>
                    <div class="text-h6">chats</div>
                        <q-list>
                            <q-item 
                                v-for="conversation in conversations" 
                                clickable 
                                @click="getConversation(conversation)"
                            >
                                <q-item-section avatar>
                                    <q-avatar color="primary" class="text-capitalize" text-color="white">
                                        <!-- {{ conversation.user.first_name[0] }} -->
                                        <div v-for="participant in conversation.participants">
                                            <div v-if="participant.id != $page.props.auth.user.id">
                                                {{ participant.first_name[0] }}
                                            </div>
                                        </div>
                                    </q-avatar>
                                </q-item-section>
                                <q-item-section>
                                    <!-- {{ conversation.user.first_name + ' ' + conversation.user.last_name }} -->
                                    <div v-for="participant in conversation.participants">
                                        <div v-if="participant.id != $page.props.auth.user.id">
                                            {{ participant.first_name + ' ' + participant.last_name }}
                                        </div>
                                    </div>
                                </q-item-section>
                            </q-item> 
                        </q-list>
                        <!-- </q-tab-panel>
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
                    </q-tab-panels> -->
                </q-card>
                dili makita dire kung wala pay conversation
            </div>
            <div class="col-8 q-pb-md">
                <CreateConversation :selected_user="selected_user" v-if="selected_user" />
                <div v-else>
                    <div class="text-center q-mt-xl" v-if="currentConversation == null">
                        You havent selected a chat...
                        <p>this shit should have the chat layout</p>
                    </div>
                    <ShowChat v-else :conversation="currentConversation" />
                </div>
            </div>
        </div>
    
</template>