<script setup>

import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

defineOptions({ layout: CustomerLayout})
const $q = useQuasar()
const message = ref('')

defineProps({ products: Object})

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
    <h1>EDIT</h1>
    <q-input v-model="message" filled label="your message"></q-input>
    {{ products }}
</template>
