<script setup>

import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const message = ref('')

Echo.channel('notify')
    .listen('HelloEvent', (data) => {
        console.log(data)
        $q.notify(data)
    })
    .error((err) => {
        console.error(err)
    })

</script>

<template>
    Test
    <q-input v-model="message" filled label="your message"></q-input>
</template>
