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
        $q.notify(e);
    })
    .listen('.OrderStatusUpdated', (e) => {
        console.log("PLS WORK BRO")
    })

// Echo.private(`private-message.1`)
//     .subscribe('SampleEvent', (e) => {
//         console.log('Event received:', e);
//         $q.notify(e);
//     })

    // Echo.channel(`message`)
    // .listen('SampleEvent', (e) => {
    //     console.log('FROM PUBLIC CHANNEL');
    //     $q.notify(e);
    // })
    // .error((err) => {
    //     console.error('Echo Error:', err);
    // });


Echo.channel('notify')
    .listen('Product\\HelloEvent', (data) => {
        console.log("boyy where u from" + data.message)
        $q.notify(data)
    }
)

</script>

<template>
    {{ order }}
</template>