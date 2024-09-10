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
        <div class="row">
            <div class="col-3" v-for="facility in facilities" >
                <q-card class="q-my-md">
                {{ facility.name }}
                <Link :href="route('customer.facilities.show', facility.id)">
                    <q-btn>button</q-btn>
                </Link>
            </q-card>
            </div>
        </div>
    </div>
</template>