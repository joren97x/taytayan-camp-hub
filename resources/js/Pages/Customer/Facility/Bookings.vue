<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import BookingCard from './Partials/BookingCard.vue'
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
                        <div class="text-h6">Bookings</div>
                        <BookingCard v-for="booking in active_bookings" :booking="booking"/>
                        <div v-show="active_bookings.length <= 0">
                            <div class="flex items-center justify-center" style="height: 100px;">
                                <q-icon name="confirmation_number" size="50px"/>
                            </div>
                            <div class="text-subtitle1 text-center">No Upcoming Bookings</div>
                        </div>
                        <div class="flex justify-center q-mt-md">
                                <q-btn label="See Past Bookings" @click="showPastBookings = !showPastBookings" no-caps flat  color="primary"/>
                            </div>
                        <div v-show="showPastBookings">
                            <div class="text-h6 q-mt-md">Past Bookings</div>
                            <BookingCard v-for="booking in past_bookings" :booking="booking"/>
                            <div v-show="past_bookings.length <= 0">
                                <div class="flex items-center justify-center" style="height: 100px;">
                                    <q-icon name="confirmation_number" size="50px"/>
                                </div>
                                <div class="text-subtitle1 text-center">No Past Bookings</div>
                            </div>
                        </div>
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