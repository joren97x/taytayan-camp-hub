<script setup>

import { useQuasar } from 'quasar'

const props = defineProps({
    order: Object
})

const $q = useQuasar()
// console.log(`Subscribing to orders.${props.order.id}`);

Echo.private(`orders.${props.order.id}`)
    .listen('Product\\OrderStatusUpdated', (e) => {
        console.log('Event received:', e);
        props.order.status = "WOW UPDATE SHAG REALTIME FAXX"
        $q.notify(e);
    })

// Echo.channel('notify')
//     .listen('Product\\HelloEvent', (data) => {
//         console.log("boyy where u from" + data.message)
//         $q.notify(data)
//     }
// )

</script>

<template>
    {{ order }}
    <h1>
        {{ $page.props.auth.user.email }}
    </h1>
</template>