import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDrawerStore = defineStore('drawer', () => {

    const drawer = ref(false)

    function toggle() {
        drawer.value = !drawer.value
    }
  
    return { drawer, toggle }
  })