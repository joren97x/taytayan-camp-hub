<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import BookingCard from './Partials/BookingCard.vue'
import { date } from 'quasar'

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
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-h6">Bookings</div>
                        <div class="flex items-center justify-center" style="height: 100px;">
                            <q-icon name="confirmation_number" size="50px"/>
                        </div>
                        <div class="text-subtitle1 text-center">No Upcoming Bookings</div>
                        <div class="flex justify-center q-mt-md">
                            <q-btn label="See Past Bookings" no-caps flat  color="primary"/>
                        </div>
                        <div class="text-h6 q-mt-md">Past Bookings</div>
                        <Link :href="route('customer.tickets.show', booking.id)" v-for="booking in bookings">
                            <q-item >
                                <!-- <q-item-section avatar class="items-center">
                                    <div class="text-weight-bold text-secondary">{{ date.formatDate(ticket_order.event.date, 'MMM') }}</div>
                                    <div>{{ date.formatDate(ticket_order.event.date, 'D') }}</div>
                                </q-item-section> -->
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" height="100px" width="200px" class="rounded-borders" />
                                </q-item-section>
                                <q-item-section top>
                                    <q-item-label class="text-subtitle1">{{ booking.facility.name }}</q-item-label>
                                    <!-- <q-item-label caption>{{ date.formatDate(booking.facility.date, 'MMM D, YYYY') + ' at ' +  booking.facility.start_time}}</q-item-label>
                                    <q-item-label caption>Purchased on {{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}(₱839.92) </q-item-label> -->
                                    <!-- Tue, Mar 19, 8:31 PM  -->
                                </q-item-section>
                            </q-item>
                        </Link>
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