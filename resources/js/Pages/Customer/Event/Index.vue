<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    events: Object
})

</script>

<template>
    <div>
        <q-card v-for="event in events" class="q-my-md">
            {{ event }}
            <Link :href="route('customer.events.show', event.id)">
                <q-btn>button</q-btn>
            </Link>
        </q-card>
    </div>
</template>