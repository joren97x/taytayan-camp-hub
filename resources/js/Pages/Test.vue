<script setup>

import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const message = ref('')
const sendMessage = () => {

}



Echo.channel('hello-channel')
    .listen('.hello.event', (data) => {
        $q.notify(data)
    }
)


</script>

<template>
    Test
    <q-input v-model="message"></q-input>
</template>
