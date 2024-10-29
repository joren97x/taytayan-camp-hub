<script setup>

import { ref } from 'vue'
import BookingCard from '../Facility/Partials/BookingCard.vue'

defineProps({
    active_bookings: Object,
    past_bookings: Object,
})

const showPastBookings = ref(false)

</script>

<template>
    <div class="text-h6">Bookings</div>
    <BookingCard v-for="booking in active_bookings" :booking="booking"/>
    <div v-show="active_bookings.length <= 0">
        <div class="flex items-center justify-center" style="height: 100px;">
            <q-icon name="confirmation_number" size="50px"/>
        </div>
        <div class="text-subtitle1 text-center">No Upcoming Bookings</div>
    </div>
    <div class="flex justify-center q-mt-md">
        <q-btn label="See Past Bookings" @click="showPastBookings = !showPastBookings" v-show="!showPastBookings" no-caps flat  color="primary"/>
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
        <div class="flex justify-center q-mt-md">
            <q-btn label="Hide Past Bookings" @click="showPastBookings = !showPastBookings" no-caps flat  color="primary"/>
        </div>
    </div>
</template>