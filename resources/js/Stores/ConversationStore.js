import axios from 'axios'
import { defineStore } from 'pinia'
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const $page = usePage()
export const useConversationStore = defineStore('conversations', () => {

    const conversations = ref([])
    const conversation = ref(null)
    const receiver = ref(null)


    // const unreadCount = computed(() => {
    //     return notifications.value.filter(notification => !notification.is_read).length
    // })

    onMounted(() => {
        console.log('from on mounted conversations store')
        if($page.props.auth.user) {
            console.log('FETCH CONVO 1')
            fetchConversations()
            fetchConversation($page.url[$page.url.length-1])
            initializeConversationsListener()
        }
    })

    watch(() => $page.props.auth.user, (newUser, oldUser) => {
        if (newUser && !oldUser) {
            fetchConversations()
            console.log('FETCH CONVO 2')
            initializeConversationsListener()
        }
    })

    const initializeConversationsListener = () => {
        Echo.private(`users.online`)
        .listen('UserStatusUpdated', (data) => {
            console.log(data)
            conversations.value.map((convo) => {
                convo.participants.find((user) => {
                    if(user.id == data.user.id) {
                        user.is_online = !user.is_online
                    }
                })
            })
            if(receiver.value.id == data.user.id) {
                receiver.value.is_online = !receiver.value.is_online
            }
            // $q.notify('Users Status Updated')
        })
    }

    const initializeConversationListener = () => {
        console.log('CONVERSATION ATTACHED')
        Echo.private(`conversation.${conversation.value.id}`)
        .listen('MessageSent', (data) => {
            console.log(data)
            conversation.value.messages.push(data.message)
            // addMessage(data.message)
        })
    }

    const fetchConversations = () => {
        // notifications.value = []
        // console.log(conversation.value)
        axios.get(route('conversations.get_conversations'))
        .then((result) => {
            // result.data.notifications.forEach(el => {
            //     notifications.value.push(el)
            // })
            console.log(result)
            conversations.value = result.data.conversations
        })
        .catch((err) => {
            console.error(err)
        })
    }

    const fetchConversation = (id) => {
        axios.get(route('conversations.get_conversation', id))
        .then((result) => {
            conversation.value = result.data.conversation
            receiver.value = conversation.value.participants.find((participant) => participant.id != $page.props.auth.user.id)
            initializeConversationListener()
        })
        .catch((err) => {
            console.error(err)
        })
    }
    // return { conversation, conversations, fetchConversation, fetchConversations, receiver }
    return { conversation, conversations, fetchConversation, fetchConversations, receiver, initializeConversationListener }

  })