<script setup>


import { useQuasar, date } from 'quasar'
import { useForm, usePage, Link } from '@inertiajs/vue3'
// import ChatLayout from '@/Layouts/ChatLayout.vue'
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

// defineOptions({
//     layout: ChatLayout
// })

const props = defineProps({
    conversation: Object
})

const conversation = ref(props.conversation)
const scrollArea = ref(null)

const $q = useQuasar()
const $page = usePage()
const form = useForm({
    message: ''
})

function sendMessage() {
    form.post(route(`messages.store`, props.conversation.id), {
        onSuccess: () => {
            form.reset()
            // $q.notify('annyeong')
        }
    })
}

function addMessage(message) {
    // console.log('helo?')
    // console.log(currentConversation.value)
    conversation.value.messages.push(message)
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
    subscribeChannel()
    scrollToBottom()
})

function subscribeChannel() {
    Echo.private(`conversation.${props.conversation.id}`)
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
                    <q-avatar v-if="receiver.profile_pic">
                        <q-img class="fit" fit="cover" :src="`/storage/${receiver.profile_pic}`"/>
                    </q-avatar>
                    <q-avatar color="primary" v-else>
                        {{ receiver.first_name[0] }}
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ receiver.first_name + ' ' + receiver.last_name }}</q-item-label>
                    <q-item-label caption>{{ receiver.is_online }}</q-item-label>
                </q-item-section>

            </q-item>
        </q-toolbar>
        <q-separator></q-separator>

        <!-- {{ currentConversation }} -->
        <div class="bg-white q-px-sm">
            <div v-if="conversation.messages.length > 0">
                <q-scroll-area ref="scrollArea" style="height: 83vh; max-width: 100%;">
                    <q-chat-message
                        v-for="message in conversation.messages"
                        name="me"
                        :avatar="$page.props.auth.user.id == message.user_id ? `/storage/${$page.props.auth.user.profile_pic}` : `/storage/${message.user.profile_pic}`"
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
                        <q-img class="fit" fit="cover" :src="`/storage/${receiver.profile_pic}`"></q-img>
                    </q-avatar>
                    <div class="q-mb-xl q-mt-sm">
                        <h6 class="q-pa-none q-ma-none">John Doe</h6>
                        <div class="text-caption">Joined on {{ date.formatDate(receiver.created_at, 'MMM D, YYYY') }}</div>
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