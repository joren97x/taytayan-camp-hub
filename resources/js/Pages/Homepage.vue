<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { useQuasar } from 'quasar'

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

const $q = useQuasar()
Echo.channel('hello-channel')
    .listen('.hello.event', (e) => {
        $q.notify(e)
    })

</script>

<template>
    <Head title="Welcome" />
    <div>
        <h1>THIS THE HOMEPAGE BRUH</h1>
        <h2>Ako pang ge una ang backend ok</h2>
        {{ $page }}
        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
    </div>
</template>


