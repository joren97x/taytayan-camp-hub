<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

defineOptions({
    layout: CustomerLayout
})




Echo.channel('hello-channel')
    .listen('.hello.event', (data) => {
        console.log(data)
    }
).error((err) => {
    console.error(err)
})

// import Pusher from 'pusher-js';

// var pusher = new Pusher('bcdca3d38dbd8b4b566c', {
//   cluster: 'ap1'
// });

// var channel = pusher.subscribe('hello-channel');
// channel.bind('say-hello', function(data) {
//     console.log(data)
// });

</script>

<template>
    <Head title="Welcome" />
    <div>
        <h1>THIS THE HOMEPAGE BRUH</h1>
        {{ $page }}
        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
    </div>
</template>


