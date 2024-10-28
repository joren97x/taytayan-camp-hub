import axios from 'axios'
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useOrderStore = defineStore('orders', () => {

    const orders = ref([])
    const preparingOrders = computed(() => {
        // return orders.value.filter(order => ['pending', 'preparing'].includes(order.status))
        return orders.value.filter(order => ['pending', 'preparing'].includes(order.status))
    
    })
    
    const readyOrders = computed(() => {
        // return orders.value.filter(order => !['pending', 'preparing'].includes(order.status))
        return orders.value.filter(order => !['pending', 'preparing'].includes(order.status))
    })

    const getOrders = () => {
        console.log('goo')
        axios.get(route('cashier.orders.get_orders'))
        .then((res) => {
            orders.value = res.data
        })
        .catch((err) => {
            console.error(err)
        })
    }

    Echo.private('orders')
    .listen('Product\\OrderStatusUpdated', (data) => {
        console.log('data')
        axios.get(route('cashier.orders.get_orders'))
        .then((res) => {
            alert('from order store')
            orders.value = res.data
        })
        .catch((err) => {
            console.error(err)
        })
    })
  
    return { orders, getOrders, readyOrders, preparingOrders }
  })