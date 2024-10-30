<script setup>


import { useQuasar, date } from 'quasar'
import { useForm, usePage, Link, Head } from '@inertiajs/vue3'
// import ChatLayout from '@/Layouts/ChatLayout.vue'
import { computed, onBeforeMount, onMounted, onUnmounted, ref, watch } from 'vue'
import { useConversationStore } from '@/Stores/ConversationStore';

// defineOptions({
//     layout: ChatLayout
// })

const conversationStore = useConversationStore()
const scrollArea = ref(null)
const $q = useQuasar()
const $page = usePage()
const form = useForm({
    message: ''
})


onBeforeMount(() => {
    conversationStore.fetchConversation($page.url[$page.url.length-1])
})


function sendMessage() {
    form.post(route(`messages.store`, conversationStore.conversation.id), {
        onSuccess: () => {
            form.reset()
            scrollToBottom()
            // $q.notify('annyeong')
        }
    })
}

// function addMessage(message) {
//     conversation.value.messages.push(message)
//     scrollToBottom()
// }

function scrollToBottom() {
    if (scrollArea.value) {
        // scrollArea.value.setScrollPosition('end');
        const contentHeight = scrollArea.value.$el.scrollHeight;
        // Scroll to the bottom
        scrollArea.value.setScrollPosition('vertical', contentHeight, 500);
    }
}

onMounted(() => {
    // console.log(conversationStore.conversation)
    // conversationStore.initializeConversationListener()
    scrollToBottom()
})

onUnmounted(() => {
    Echo.leave(`conversation.${conversationStore.conversation.id}`)
    Echo.leave(`users.online.${conversationStore.receiver.id}`)
})

</script>

<template>
    <Head :title="conversationStore.receiver?.first_name + ' ' + conversationStore.receiver?.last_name" />
    <!-- <div class="q-pa-md">
        <q-card bordered flat>
            <q-card-section>
                {{ conversation }}
            </q-card-section>
        </q-card>
    </div> -->
    <q-card style="height: 95vh;" bordered flat :class="$q.screen.gt.sm ? 'q-mx-sm' : ''">
        <q-toolbar class="bg-white q-pa-none">
            <q-item>
                <q-item-section avatar class="lt-md">
                    <Link :href="route('conversations.index')">
                        <q-btn round icon="arrow_back" unelevated/>
                    </Link>
                </q-item-section>
                <q-item-section avatar>
                    <q-avatar v-if="conversationStore.receiver?.profile_pic">
                        <q-img class="fit" fit="cover" :src="`/storage/${conversationStore.receiver?.profile_pic}`"/>
                    </q-avatar>
                    <q-avatar color="primary" v-else>
                        {{ conversationStore.receiver?.first_name[0] }}
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ conversationStore.receiver?.first_name + ' ' + conversationStore.receiver?.last_name }}</q-item-label>
                    <q-item-label caption>
                        <div v-if="conversationStore.receiver?.is_online">
                            <q-icon name="circle" color="green"/>
                            Active now
                        </div>
                        <div v-else>
                            umm                            
                        </div>
                    </q-item-label>
                </q-item-section>

            </q-item>
        </q-toolbar>
        <q-separator></q-separator>

        <!-- {{ currentConversation }} -->
        <div class="bg-white q-px-sm">
            <div v-if="conversationStore.conversation?.messages?.length > 0">
                <q-scroll-area ref="scrollArea" style="height: 83vh; max-width: 100%;">
                    <q-chat-message
                        v-for="message in conversationStore.conversation.messages"
                        name="me"
                        :avatar="$page.props.auth.user.id == message.user_id ? `/storage/${$page.props.auth.user.profile_pic}` : `/storage/${conversationStore.receiver?.profile_pic}`"
                        :text="[message.message]"
                        :text-color="$page.props.auth.user.id == message.user_id ? 'white' : 'black'"
                        :bg-color="$page.props.auth.user.id == message.user_id ? 'blue' : 'grey'"
                        :sent="$page.props.auth.user.id == message.user_id"
                    />
                </q-scroll-area>
            </div>
            <div v-else style="height: 80vh" class="flex justify-center items-end text-center">
                <div>
                    <q-avatar size="100px" color="blue">
                        <q-img class="fit" fit="cover" :src="`/storage/${conversationStore.receiver?.profile_pic}`"></q-img>
                    </q-avatar>
                    <div class="q-mb-xl q-mt-sm">
                        <h6 class="q-pa-none q-ma-none">{{ conversationStore.receiver?.first_name + ' ' + conversationStore.receiver?.last_name }}</h6>
                        <div class="text-caption">Joined on {{ date.formatDate(conversationStore.receiver?.created_at, 'MMM D, YYYY') }}</div>
                    </div>
                </div>
            </div>
            <!-- <p class="text-center" v-if="conversation == null">To start a conversation send them a message.</p> -->
            <q-form @submit="sendMessage()">
                <q-input label="Send a message..." outlined rounded v-model="form.message">
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
    </q-card>
   
</template>