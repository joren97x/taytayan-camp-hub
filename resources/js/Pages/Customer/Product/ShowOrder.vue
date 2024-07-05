<script setup>

import { useQuasar } from 'quasar'

const props = defineProps({
    order: Object
})

const $q = useQuasar()
Echo.private(`orders.${props.order.id}`)
    .listen('.OrderStatusUpdated', (e) => {
        console.log(e)
        $q.notify(e)
    })

// Echo.channel('hello-channel')
//     .listen('.hello.event', (e) => {
//         $q.notify(e)
//     })

Echo.channel('hello-channel')
    .listen('.hello.event', (data) => {
        $q.notify(data)
    }
)

</script>

<template>
    {{ order }}
</template>