<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import FacilityCard from './Partials/FacilityCard.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    <Head title="Facilities" />
    <div>
        <div style="height: 40vh;" class="full-width bg-grey row justify-center">
        </div>
        
        <div class="q-ma-md">
            <div class="text-h6">Lorem Ipsum</div>

            <div class="row q-col-gutter-md">
                <div class="col-3" v-for="facility in facilities">
                    <Link :href="route('customer.facilities.show', facility.id)">
                        <FacilityCard :facility="facility" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

a {
    text-decoration: none;
}

</style>
<!-- <div class="row">
    <div class="col-3" v-for="facility in facilities" >
        <q-card class="q-my-md">
        {{ facility.name }}
        <Link :href="route('customer.facilities.show', facility.id)">
            <q-btn>button</q-btn>
        </Link>
    </q-card>
    </div>
</div> -->