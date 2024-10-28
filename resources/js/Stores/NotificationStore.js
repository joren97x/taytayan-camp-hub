import axios from 'axios'
import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
export const useNotificationStore = defineStore('notifications', () => {

    const notifications = ref([])
    const unreadCount = computed(() => {
        return notifications.value.filter(notification => !notification.is_read).length;
    });

    onMounted(() => {
        console.log('from on mounted notifications store')
        fetchNotifications()
    })

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

    // Echo.private('orders')
    // .listen('Product\\OrderStatusUpdated', (data) => {
    //     console.log('data')
    //     axios.get(route('cashier.orders.get_orders'))
    //     .then((res) => {
    //         alert('from order store')
    //         orders.value = res.data
    //     })
    //     .catch((err) => {
    //         console.error(err)
    //     })
    // })
  
    return { notifications, unreadCount, readNotifications, fetchNotifications }
  })