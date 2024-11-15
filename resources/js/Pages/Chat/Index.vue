<script setup>

import { usePage, Head, Link } from '@inertiajs/vue3'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { useQuasar } from 'quasar'
import ChatItem from './ChatItem.vue'
import { useConversationStore } from '@/Stores/ConversationStore'

const props = defineProps({
    conversations: Object,
    role: String
})

const conversationStore = useConversationStore()
const drawerStore = useDrawerStore()
const $page = usePage()
const $q = useQuasar()

// Echo.private(`users.online`)
//     .listen('UserStatusUpdated', (data) => {
//         console.log(data)
//         conversations.value.map((convo) => {
//             convo.participants.find((user) => {
//                 if(user.id == data.user.id) {
//                     user.is_online = !user.is_online
//                 }
//             })
//         })
//         $q.notify('Users Status Updated')
//     })
function goBack() {
  window.history.back(); // Goes back to the previous page in the browser history
}
</script>

<template>
    <!-- <component :is="layoutComponent"> -->
        <Head title="Inbox" />
        <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <q-card bordered flat style="height: 95vh;">
                        <q-card-actions class="lt-md">
                            <Link :href="route('homepage')" class="text-primary text-h6 text-weight-bolder">
                                <q-avatar size="40px" class="q-mr-sm">
                                    <q-img src="../logo.jpg" fill="cover" />
                                </q-avatar>
                            </Link>
                            <!-- bruh -->
                            <div class="text-h6 q-ml-sm">Chats</div>
                        </q-card-actions>
                        <q-list class="q-pa-sm">
                            <!-- {{ layoutComponent }} -->
                            <!-- <q-btn icon="menu" class="lt-md" flat @click="drawerStore.drawer = true"/> -->
                            <div class="text-h6 q-ml-sm q-mt-sm gt-sm">Chats</div>
                            <ChatItem v-for="conversation in conversationStore.conversations" :conversation="conversation" />
                            <!-- <Link :href="route('conversations.show', conversation.id)" v-for="conversation in conversations" >
                                <q-item 
                                    clickable 
                                    class="rounded-borders" 
                                    :active="$page.url[$page.url.length-1] == conversation.id" 
                                    active-class="bg-grey text-white"
                                >
                                    <q-item-section avatar>
                                        <div v-for="participant in conversation.participants">
                                            <div v-if="participant.id != $page.props.auth.user.id">
                                                <q-avatar color="primary" class="text-capitalize" text-color="white">
                                                    <q-img v-if="participant.profile_pic" :src="`/storage/${participant.profile_pic}`" class="fit" fit="cover"></q-img>
                                                    <div v-else>{{ participant.first_name[0] }}</div>
                                                </q-avatar>
                                            </div>
                                        </div>
                                    </q-item-section>
                                    <q-item-section>
                                        <div v-for="participant in conversation.participants">
                                            <div v-if="participant.id != $page.props.auth.user.id">
                                                {{ participant.first_name + ' ' + participant.last_name }}
                                            </div>
                                        </div>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-icon name="circle" color="green" size="10px"/>
                                    </q-item-section>
                                </q-item>
                            </Link> -->
                            <div v-if="conversationStore.conversations.length == 0" class="text-center q-my-md">
                                You dont have any conversations...
                            </div>
                        </q-list>
                    </q-card>
                </div>
                <div class="col-md-9 col-xs-12 col-sm-12 col-lg-9 col-xl-9 bg-grey-3">
                    <div 
                        style="height: 80vh; width: 100%;" 
                        class="justify-center items-center flex text-h6 gt-sm" 
                        v-if="
                            $page.component == 'Cashier/Inbox' ||
                            $page.component == 'Customer/Inbox' ||
                            $page.component == 'Admin/Inbox' ||
                            $page.component == 'Driver/Inbox'
                        ">
                        <q-icon name="chat" size="lg" class="q-mt-xs q-mr-md" />
                        <div>
                            Choose a conversation
                        </div>
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    <!-- </component> -->
</template>

<style scoped>

a {
    text-decoration: none;
    color: black;
}

</style>