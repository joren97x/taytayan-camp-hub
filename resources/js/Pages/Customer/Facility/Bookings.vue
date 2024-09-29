<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import BookingCard from './Partials/BookingCard.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    bookings: Object,
})


</script>

<template>
    <Head title="Bookings"/>
    <div class="q-pa-md">
        <q-card flat bordered>
            <q-card-section>
                <div class="row">
                    <div class="col-6">
                         <div class="text-h6">Bookings</div>
                    </div>
                    <div class="col-6 text-right">
                        <!-- <Link :href="route('customer.bookings.past_bookings')"> -->
                            <q-btn label="See Past Bookings" no-caps rounded color="primary"/>
                        <!-- </Link> -->
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12" v-for="(booking, index) in bookings" :key="index">
                        <BookingCard :booking="booking" />
                    </div>
                </div>
                
            </q-card-section>
        </q-card>
    </div>
    
</template>