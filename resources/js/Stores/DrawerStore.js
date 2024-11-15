import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useDrawerStore = defineStore('drawer', () => {

    const cart = ref(0)
    const drawer = ref(false)

    function toggle() {
        drawer.value = !drawer.value
    }

    const getCartLength = () => {
        axios.get(route('customer.cart.length'))
        .then((res) => {
            // console.log(res)
            // $q.notify(res.data.cart_length)
            cart.value = res.data.cart_length
        })
    }
  
    return { drawer, toggle, getCartLength, cart }
  })