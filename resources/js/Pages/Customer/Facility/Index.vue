<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    <div>
        <q-card v-for="facility in facilities" class="q-my-md">
            {{ facility }}
            <Link :href="route('customer.facilities.show', facility.id)">
                <q-btn>button</q-btn>
            </Link>
        </q-card>
    </div>
</template>