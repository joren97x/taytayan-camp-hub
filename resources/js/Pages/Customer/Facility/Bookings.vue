<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Bookings from '../Partials/Bookings.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    active_bookings: Object,
    past_bookings: Object,
})

const showPastBookings = ref(false)

</script>

<template>
    <Head title="Bookings"/>
    <div class="q-pa-md">
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <Bookings :active_bookings="active_bookings" :past_bookings="past_bookings" />
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
    <!-- <div class="q-pa-md">
        <q-card flat bordered>
            <q-card-section>
                <div class="row">
                    <div class="col-6">
                         <div class="text-h6">Bookings</div>
                    </div>
                    <div class="col-6 text-right">
                            <q-btn label="See Past Bookings" no-caps rounded color="primary"/>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12" v-for="(booking, index) in bookings" :key="index">
                        <BookingCard :booking="booking" />
                    </div>
                </div>
                
            </q-card-section>
        </q-card>
    </div> -->
    
</template>