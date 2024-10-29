import axios from 'axios'
import { defineStore } from 'pinia'
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
export const useNotificationStore = defineStore('notifications', () => {

    const notifications = ref([])
    const unreadCount = computed(() => {
        return notifications.value.filter(notification => !notification.is_read).length
    })

    onMounted(() => {
        console.log('from on mounted notifications store')
        if(page.props.auth.user) {
            fetchNotifications()
            initializeNotificationListener()
        }
    })

    watch(() => page.props.auth.user, (newUser, oldUser) => {
        if (newUser && !oldUser) {
            fetchNotifications()
            initializeNotificationListener()
        }
    })

    const initializeNotificationListener = () => {
        Echo.private(`notifications.${page.props.auth.user.id}`)
            .listen('Notify', (data) => {
                notifications.value.unshift(data.notification)
                console.log('New notification received:', data.notification)
            })
    }

    const fetchNotifications = () => {
        // notifications.value = []
        axios.get(route('customer.notifications.index'))
        .then((result) => {
            // result.data.notifications.forEach(el => {
            //     notifications.value.push(el)
            // })
            notifications.value = result.data.notifications
        })
        .catch((err) => {
            console.error(err)
        })
    }

    const readNotifications = () => {
        console.log('read it')
        axios.put(route('customer.notifications.read_notifications'))
        .then((res) => {
            console.log(res)
            // if(res.status == 200) {
            //     notifications.value.map((notification) => notification.is_read = true)
            // }
            // console.log('from da readNotifcations function')
            fetchNotifications()
        })
        .catch((err) => {
            console.err('something went wrong', err)
        })
    }

    return { notifications, unreadCount, readNotifications, fetchNotifications }
  })