<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import BookingCard from './Partials/BookingCard.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Profile from '../Profile.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    bookings: Object,
})

const showPastBookings = ref(false)

</script>

<template>
    <Head title="Bookings"/>
    <Profile>
    <q-card bordered flat :square="$q.screen.lt.md">
        <q-card-actions class="text-center justify-center items-center flex">
            <Link :href="route('customer.profile')" class="lt-md">
                <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
            </Link>
                <div class="text-h6">Bookings</div>
        </q-card-actions>
        <q-separator />
        <q-card-section :class="$q.screen.lt.md ? 'q-pa-none' : ''">
            <!-- <Link :href="route('customer.profile')" class="text-h6">
                <q-icon name="arrow_back"></q-icon> Bookings
            </Link>
            <div class="text-h6">Bookings</div> -->
            <div class="row reverse justify-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <BookingCard v-for="booking in bookings" :booking="booking"/>
                    <div v-show="bookings.length <= 0">
                        <div class="flex items-center justify-center" style="height: 100px;">
                            <q-icon name="confirmation_number" size="50px"/>
                        </div>
                        <div class="text-subtitle1 text-center">No Upcoming/Active Bookings</div>
                    </div>
                </div>
            </div>
        </q-card-section>
    </q-card>
</Profile>
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